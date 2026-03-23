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
use App\Modules\V1\Features\Domain\Models\DynamicFeatures;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Facades\DB;

final readonly class RequestCapacityUpgradeUseCase
{
    public function __construct(
        private ProrationService $prorationService,
        private DynamicFeatures $capacityFeature,
    ) {
    }

    public function execute(Platform $platform, int $newCapacity): PlatformPendingChange
    {
        if ($newCapacity <= $platform->capacity) {
            throw new \DomainException('New capacity must be greater than current.');
        }

        return DB::transaction(function () use ($platform, $newCapacity) {
            $daysRemaining = $this->prorationService->daysRemaining($platform);

            $oldPrice = $this->capacityFeature->quantityPrice($platform->capacity);
            $newPrice = $this->capacityFeature->quantityPrice($newCapacity);
            $amount = $this->prorationService->dynamicProration($oldPrice, $newPrice, $daysRemaining);

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
                'type' => InvoiceItemType::CAPACITY,
                'label' => 'capacity',
                'quantity' => $newCapacity - $platform->capacity,
                'unit_price' => $newPrice - $oldPrice,
                'amount' => $amount,
                'period_start' => now()->toDateString(),
                'period_end' => $platform->renew_at,
            ]);

            return PlatformPendingChange::create([
                'platform_id' => $platform->id,
                'invoice_id' => $invoice->id,
                'change_type' => PendingChangeType::INCREASE_CAPACITY,
                'payload' => [
                    'old_capacity' => $platform->capacity,
                    'new_capacity' => $newCapacity,
                ],
                'status' => PendingChangeStatus::PENDING,
            ]);
        });
    }
}

