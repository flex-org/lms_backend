<?php

namespace App\Modules\V1\Features\Domain\Repositories;

use Illuminate\Support\Collection;

interface FeatureRepositoryInterface
{
    public function list(bool $active);

    public function listDynamic();

    public function listByIds(array $ids): Collection;

    public function listByKeys(array $keys): Collection;

    public function findOrFailById(int $id, bool $active = true);

    public function findOrFailByKey(string $key, bool $active = true);
}
