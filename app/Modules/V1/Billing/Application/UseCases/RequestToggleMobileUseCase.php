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
use App\Modules\V1\Features\Domain\Enums\DynamicFeaturesValue;
use App\Modules\V1\Features\Domain\Models\DynamicFeatures;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Facades\DB;

final readonly class RequestToggleMobileUseCase
{
    public function __construct(
        private ProrationService $prorationService,
        private DynamicFeatures $mobileFeature,
    ) {
    }

    public function execute(Platform $platform): ?PlatformPendingChange
    {
        $mobilePrice = $this->mobileFeature->quantityPrice(DynamicFeaturesValue::MOBILE_APP, 1);
        if ($platform->has_mobile_app) {
            return DB::transaction(function () use ($platform, $mobilePrice) {
                $platform->has_mobile_app = false;
                $platform->cost = max(0, (float) $platform->cost - $mobilePrice);
                $platform->save();
            });
        }

        return DB::transaction(function () use ($platform, $mobilePrice) {
            $daysRemaining = $this->prorationService->daysRemaining($platform);
            $amount = $this->prorationService->featureProration($mobilePrice, $daysRemaining);

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
                'type' => InvoiceItemType::MOBILE_APP,
                'label' => 'mobile_app',
                'quantity' => 1,
                'unit_price' => $mobilePrice,
                'amount' => $amount,
                'period_start' => now()->toDateString(),
                'period_end' => $platform->renew_at,
            ]);

            $pendingChange = PlatformPendingChange::create([
                'platform_id' => $platform->id,
                'invoice_id' => $invoice->id,
                'change_type' => PendingChangeType::ENABLE_MOBILE,
                'payload' => [],
                'status' => PendingChangeStatus::PENDING,
            ]);
            $this->temporaryActivation($invoice, $pendingChange);
            return $pendingChange;
        });
    }

    private function temporaryActivation($invoice, $pendingChange)
    {
        $invoice->status = InvoiceStatus::PAID;
        $invoice->paid_at = now();
        $invoice->save();

        (new ApplyPendingChangeUseCase)->execute($pendingChange);
    }
}

