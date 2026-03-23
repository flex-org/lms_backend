<?php

namespace App\Modules\V1\Billing\Domain\Services;

use App\Modules\V1\Billing\Domain\Enums\InvoiceItemType;
use App\Modules\V1\Billing\Domain\Enums\InvoiceStatus;
use App\Modules\V1\Billing\Domain\Enums\InvoiceType;
use App\Modules\V1\Billing\Domain\Models\Invoice;
use App\Modules\V1\Billing\Domain\Models\InvoiceItem;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use App\Modules\V1\Platforms\Domain\Services\PlatformPricingService;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

class BillingCycleService
{
    public function __construct(
        private readonly PlatformPricingService $pricingService,
    ) {
    }

    public function generateMonthlyInvoice(Platform $platform, CarbonInterface $periodStart, CarbonInterface $periodEnd): Invoice
    {
        return DB::transaction(function () use ($platform, $periodStart, $periodEnd) {
            $features = $platform->features()->get();

            $amount = $this->pricingService->calculate(
                $features,
                [
                    'storage' => $platform->storage,
                    'capacity' => $platform->capacity,
                    'mobile_app' => $platform->has_mobile_app,
                ],
                30
            );

            $invoice = Invoice::create([
                'platform_id' => $platform->id,
                'type' => InvoiceType::MONTHLY,
                'status' => InvoiceStatus::PENDING,
                'amount' => $amount,
                'period_start' => $periodStart->toDateString(),
                'period_end' => $periodEnd->toDateString(),
                'due_at' => $periodEnd->toDateString(),
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'type' => InvoiceItemType::FEATURE,
                'label' => 'Monthly subscription',
                'quantity' => 1,
                'unit_price' => $amount,
                'amount' => $amount,
                'period_start' => $periodStart->toDateString(),
                'period_end' => $periodEnd->toDateString(),
            ]);

            return $invoice;
        });
    }
}

