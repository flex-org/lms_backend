<?php

namespace App\Modules\V1\Platforms\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Features\Domain\Enums\DynamicFeaturesValue;
use App\Modules\V1\Features\Domain\Models\DynamicFeatures;
use App\Modules\V1\Platforms\Domain\Repositories\DynamicFeatureRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentDynamicFeatureRepository implements DynamicFeatureRepositoryInterface
{
    public function getAllActive(): Collection
    {
        return DynamicFeatures::whereIn('name', DynamicFeaturesValue::values())->get();
    }
}
