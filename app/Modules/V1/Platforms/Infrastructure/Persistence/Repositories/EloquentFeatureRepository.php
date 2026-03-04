<?php

namespace App\Modules\V1\Platforms\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Features\Domain\Models\Feature;
use App\Modules\V1\Platforms\Domain\Repositories\FeatureRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentFeatureRepository implements FeatureRepositoryInterface
{
    public function getByIds(array $ids): Collection
    {
        return Feature::whereIn('id', $ids)->get();
    }
}
