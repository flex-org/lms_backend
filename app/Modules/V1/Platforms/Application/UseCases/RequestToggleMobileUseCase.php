<?php

namespace App\Modules\V1\Platforms\Application\UseCases;

use App\Events\ProrationInvoiceCreated;
use App\Modules\V1\Billing\Domain\Enums\InvoiceItemType;
use App\Modules\V1\Billing\Domain\Enums\PendingChangeType;
use App\Modules\V1\Billing\Domain\Models\Invoice;
use App\Modules\V1\Billing\Domain\Repositories\InvoiceRepositoryInterface;
use App\Modules\V1\Billing\Domain\Services\ProrationService;
use App\Modules\V1\Features\Domain\Enums\DynamicFeaturesValue;
use App\Modules\V1\Features\Domain\Models\DynamicFeatures;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Facades\DB;

final readonly class RequestToggleMobileUseCase
{
    public function __construct(
        private ProrationService $prorationService,
        private DynamicFeatures $mobileFeature,
        private InvoiceRepositoryInterface $invoiceRepository,
    ) {
    }

    public function execute(Platform $platform): ?Invoice
    {
        $mobilePrice = $this->mobileFeature->quantityPrice(DynamicFeaturesValue::MOBILE_APP, 1);
        if ($platform->has_mobile_app) {
            return DB::transaction(function () use ($platform, $mobilePrice) {
                $platform->has_mobile_app = false;
                $platform->cost = max(0, (float) $platform->cost - $mobilePrice);
                $platform->save();
            });
        }

        $daysRemaining = $this->prorationService->daysRemaining($platform);
        $amount = $this->prorationService->featureProration($mobilePrice, $daysRemaining);

        return DB::transaction(function () use ($platform, $mobilePrice, $amount) {

            $invoice = $this->invoiceRepository->createProration(
                platform: $platform,
                type: InvoiceItemType::MOBILE_APP,
                label: 'mobile_app',
                amount: $amount,
                quantity: 1,
                unitPrice: $mobilePrice,
            );

            ProrationInvoiceCreated::dispatch(
                $platform,
                $invoice,
                PendingChangeType::ENABLE_MOBILE,
                []
            );

            return $invoice;
        });
    }
}

