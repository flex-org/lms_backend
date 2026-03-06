<?php

namespace App\Modules\V1\Platforms\Domain\Repositories;

use Illuminate\Support\Collection;

interface DynamicFeatureRepositoryInterface
{
    public function getAllActive(): Collection;
}
