<?php

namespace App\Modules\V1\Billing\Application\UseCases;

use App\Modules\V1\Billing\Domain\Enums\InvoiceItemType;
use App\Modules\V1\Billing\Domain\Enums\InvoiceStatus;
use App\Modules\V1\Billing\Domain\Enums\InvoiceType;
use App\Modules\V1\Billing\Domain\Enums\PendingChangeStatus;
use App\Modules\V1\Billing\Domain\Enums\PendingChangeType;
use App\Modules\V1\Billing\Domain\Models\Invoice;
use App\Modules\V1\Billing\Domain\Models\InvoiceItem;
use App\Modules\V1\Billing\Domain\Models\PlatformPendingChange;
use App\Modules\V1\Billing\Domain\Services\ProrationService;
use App\Modules\V1\Features\Domain\Models\Feature;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Facades\DB;

final readonly class RequestAddFeatureUseCase
{
    public function __construct(
        private ProrationService $prorationService,
    ) {
    }

    public function execute(Platform $platform, Feature $feature): PlatformPendingChange
    {
        if ($platform->features()->whereKey($feature->id)->exists()) {
            throw new \DomainException('Feature already attached to platform.');
        }

        return DB::transaction(function () use ($platform, $feature) {
            $daysRemaining = $this->prorationService->daysRemaining($platform);
            $amount = $this->prorationService->featureProration((float) $feature->price, $daysRemaining);

            $invoice = Invoice::create([
                'platform_id' => $platform->id,
                'type' => InvoiceType::PRORATION,
                'status' => InvoiceStatus::PENDING,
                'amount' => $amount,
                'period_start' => now()->toDateString(),
                'period_end' => $platform->renew_at,
                'due_at' => now()->toDateString(),
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'type' => InvoiceItemType::FEATURE,
                'label' => $feature->key,
                'quantity' => 1,
                'unit_price' => $feature->price,
                'amount' => $amount,
                'period_start' => now()->toDateString(),
                'period_end' => $platform->renew_at,
            ]);

            return PlatformPendingChange::create([
                'platform_id' => $platform->id,
                'invoice_id' => $invoice->id,
                'change_type' => PendingChangeType::ADD_FEATURE,
                'payload' => ['feature_id' => $feature->id],
                'status' => PendingChangeStatus::PENDING,
            ]);
        });
    }
}

