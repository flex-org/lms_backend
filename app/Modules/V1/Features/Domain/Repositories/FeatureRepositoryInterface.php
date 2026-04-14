<?php

namespace App\Modules\V1\Features\Domain\Repositories;

interface FeatureRepositoryInterface
{
    public function listByPlatform(bool $active);

    public function list(bool $active);

    public function findOrFailById(int $id, bool $active = true);

    public function findOrFailByKey(string $key, bool $active = true);
}
