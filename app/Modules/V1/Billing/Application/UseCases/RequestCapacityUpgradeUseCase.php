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

final readonly class RequestCapacityUpgradeUseCase
{
    public function __construct(
        private ProrationService $prorationService,
        private DynamicFeatures $capacityFeature,
    ) {
    }

    public function execute(Platform $platform, int $newCapacity): ?PlatformPendingChange
    {
        if ($newCapacity = $platform->capacity) {
            throw new \DomainException(__('billing.capacity_must_differ'));
        }
        $oldPrice = $this->capacityFeature->quantityPrice(DynamicFeaturesValue::CAPACITY, $platform->capacity);
        $newPrice = $this->capacityFeature->quantityPrice(DynamicFeaturesValue::CAPACITY, $newCapacity);

        return ($newCapacity < $platform->capacity)
            ? $this->reduceCapacity($platform, $newCapacity, $oldPrice, $newPrice)
            : $this->increaseCapacity($platform, $newCapacity, $oldPrice, $newPrice);
    }

    public function reduceCapacity(Platform $platform, int $newCapacity, $oldPrice, $newPrice)
    {
        return DB::transaction(function () use ($platform, $newCapacity, $oldPrice, $newPrice) {
            $diff = max(0, $oldPrice - $newPrice);
            $platform->capacity = $newCapacity;
            $platform->cost = max(0, (float) $platform->cost - $diff);
            $platform->save();
        });

    }

    public function increaseCapacity(Platform $platform, int $newCapacity, $oldPrice, $newPrice)
    {
        return DB::transaction(function () use ($platform, $newCapacity, $oldPrice, $newPrice) {
            $daysRemaining = $this->prorationService->daysRemaining($platform);

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
            $pendingChange = PlatformPendingChange::create([
                'platform_id' => $platform->id,
                'invoice_id' => $invoice->id,
                'change_type' => PendingChangeType::INCREASE_CAPACITY,
                'payload' => [
                    'old_capacity' => $platform->capacity,
                    'new_capacity' => $newCapacity,
                ],
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

