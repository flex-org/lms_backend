<?php

namespace App\Modules\V1\Platforms\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Platforms\Domain\Repositories\PlatformRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Collection;

class EloquentPlatformRepository implements PlatformRepositoryInterface
{
    public function create(array $attributes): Platform
    {
        return Platform::create($attributes);
    }

    public function attachSellingSystems(Platform $platform, array $sellingSystems): void
    {
        $platform->sellingSystems()->attach($sellingSystems);
    }

    public function attachFeatures(Platform $platform, Collection $features): void
    {
        $platform->features()->attach(
            $features->pluck('id')->mapWithKeys(fn ($id) => [
                $id => ['price' => $features->firstWhere('id', $id)['price']],
            ])
        );
    }

    public function giveFeaturePermissions(Platform $platform, Collection $features): void
    {
        $permissions = $features->map(fn ($feature) => 'feature-' . $feature['id'])->toArray();
        $platform->givePermissionTo($permissions);
    }

    public function domainExists(string $domain): bool
    {
        return Platform::where('domain', $domain)->exists();
    }
}
