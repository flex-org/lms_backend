<?php

namespace App\Modules\V1\Platforms\Application\UseCases;

use App\Events\ProrationInvoiceCreated;
use App\Modules\V1\Billing\Domain\Enums\InvoiceItemType;
use App\Modules\V1\Billing\Domain\Enums\PendingChangeType;
use App\Modules\V1\Billing\Domain\Models\Invoice;
use App\Modules\V1\Billing\Domain\Repositories\InvoiceRepositoryInterface;
use App\Modules\V1\Billing\Domain\Services\ProrationService;
use App\Modules\V1\Features\Domain\Repositories\FeatureRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Facades\DB;

final readonly class RequestAddFeatureUseCase
{
    public function __construct(
        private ProrationService $prorationService,
        private FeatureRepositoryInterface $featureRepository,
        private InvoiceRepositoryInterface $invoiceRepository,
    ) {
    }

    public function execute(Platform $platform, string $key) :Invoice
    {
        $feature = $this->featureRepository->findOrFailByKey($key);

        if ($platform->features()->whereKey($feature->id)->exists()) {
            throw new \DomainException(__('billing.feature_already_attached'));
        }

        $daysRemaining = $this->prorationService->daysRemaining($platform);
        $amount = $this->prorationService->featureProration(
            $feature->price,
            $daysRemaining
        );

        return DB::transaction(function () use ($platform, $feature, $amount) {

            $invoice = $this->invoiceRepository->createProration(
                platform: $platform,
                type: InvoiceItemType::FEATURE,
                label: $feature->key,
                amount: $amount,
                quantity: 1,
                unitPrice: $feature->price,
            );

            ProrationInvoiceCreated::dispatch(
                $platform,
                $invoice,
                PendingChangeType::ADD_FEATURE,
                ['feature_id' => $feature->id]
            );

            return $invoice;
        });
    }

}

