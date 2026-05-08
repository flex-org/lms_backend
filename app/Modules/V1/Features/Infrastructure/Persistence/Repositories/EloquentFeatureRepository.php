<?php

namespace App\Modules\V1\Features\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Features\Domain\Models\DynamicFeatures;
use App\Modules\V1\Features\Domain\Models\Feature;
use App\Modules\V1\Features\Domain\Repositories\FeatureRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentFeatureRepository implements FeatureRepositoryInterface
{

    public function list(bool $active)
    {
        return Feature::when($active, fn($query) => $query->where('active', true))
            ->get();
    }

    public function listByIds(array $ids): Collection
    {
        return Feature::whereIn('id', $ids)->get();
    }
    public function listByKeys(array $keys): Collection
    {
        return Feature::whereIn('key', $keys)->get();
    }

    public function findOrFailById(int $id ,bool $active = true)
    {
        return Feature::where('id', $id)
            ->when($active, fn($query) => $query->where('active', true))
            ->firstOrFail();
    }

    public function findOrFailByKey(string $key, bool $active = true)
    {
        return Feature::where('key', $key)
            ->when($active, fn($query) => $query->where('active', true))
            ->firstOrFail();
    }


    public function listDynamic(): Collection
    {
        return DynamicFeatures::get();
    }
}
