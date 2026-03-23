<?php

namespace App\Modules\V1\Billing\Application\UseCases;

use App\Modules\V1\Features\Domain\Models\DynamicFeatures;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Facades\DB;

final readonly class ReduceStorageUseCase
{
    public function __construct(
        private DynamicFeatures $storageFeature,
    ) {
    }

    public function execute(Platform $platform, int $newStorage): void
    {
        if ($newStorage >= $platform->storage) {
            throw new \DomainException('New storage must be less than current.');
        }

        DB::transaction(function () use ($platform, $newStorage) {
            $oldPrice = $this->storageFeature->quantityPrice($platform->storage);
            $newPrice = $this->storageFeature->quantityPrice($newStorage);

            $diff = max(0, $oldPrice - $newPrice);

            $platform->storage = $newStorage;
            $platform->cost = max(0, (float) $platform->cost - $diff);
            $platform->save();
        });
    }
}

