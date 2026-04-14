<?php

namespace App\Modules\V1\Features\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Features\Domain\Models\Feature;
use App\Modules\V1\Features\Domain\Repositories\FeatureRepositoryInterface;

class EloquentFeatureRepository implements FeatureRepositoryInterface
{
    public function listByPlatform(bool $active)
    {
        // TODO: Implement listByPlatform() method.
    }

    public function list(bool $active)
    {
        return Feature::when($active, fn($query) => $query->where('active', true))
            ->get();
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
}
