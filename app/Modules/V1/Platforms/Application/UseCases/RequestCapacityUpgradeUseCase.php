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

final readonly class RequestCapacityUpgradeUseCase
{
    public function __construct(
        private ProrationService $prorationService,
        private DynamicFeatures $capacityFeature,
        private InvoiceRepositoryInterface $invoiceRepository,
    ) {
    }

    public function execute(Platform $platform, int $newCapacity): ?Invoice
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
        $daysRemaining = $this->prorationService->daysRemaining($platform);
        $amount = $this->prorationService->dynamicProration($oldPrice, $newPrice, $daysRemaining);
        return DB::transaction(function () use ($platform, $amount, $newCapacity, $oldPrice, $newPrice) {

            $invoice = $this->invoiceRepository->createProration(
                platform: $platform,
                type: InvoiceItemType::CAPACITY,
                label: 'capacity',
                amount: $amount,
                quantity: $newCapacity - $platform->capacity,
                unitPrice: $newPrice - $oldPrice,
            );

            ProrationInvoiceCreated::dispatch(
                $platform,
                $invoice,
                PendingChangeType::INCREASE_CAPACITY,
                [
                    'old_capacity' => $platform->capacity,
                    'new_capacity' => $newCapacity,
                ]
            );

            return $invoice;
        });
    }

}

