<?php

namespace App\Modules\V1\Platforms\Domain\Repositories;

use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Collection;

interface PlatformRepositoryInterface
{
    public function create(array $attributes): Platform;

    public function attachSellingSystems(Platform $platform, array $sellingSystems): void;

    public function attachFeatures(Platform $platform, Collection $features): void;

    public function giveFeaturePermissions(Platform $platform, Collection $features): void;

    public function domainExists(string $domain): bool;
}
