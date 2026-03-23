<?php

namespace App\Modules\V1\Billing\Application\UseCases;

use App\Modules\V1\Features\Domain\Models\DynamicFeatures;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Facades\DB;

final readonly class ReduceCapacityUseCase
{
    public function __construct(
        private DynamicFeatures $capacityFeature,
    ) {
    }

    public function execute(Platform $platform, int $newCapacity): void
    {
        if ($newCapacity >= $platform->capacity) {
            throw new \DomainException('New capacity must be less than current.');
        }

        DB::transaction(function () use ($platform, $newCapacity) {
            $oldPrice = $this->capacityFeature->quantityPrice($platform->capacity);
            $newPrice = $this->capacityFeature->quantityPrice($newCapacity);

            $diff = max(0, $oldPrice - $newPrice);

            $platform->capacity = $newCapacity;
            $platform->cost = max(0, (float) $platform->cost - $diff);
            $platform->save();
        });
    }
}

