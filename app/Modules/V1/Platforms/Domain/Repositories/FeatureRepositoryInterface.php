<?php

namespace App\Modules\V1\Platforms\Domain\Repositories;

use Illuminate\Support\Collection;

interface FeatureRepositoryInterface
{
    public function getByIds(array $ids): Collection;
    public function getByKeys(array $keys): Collection;
}
