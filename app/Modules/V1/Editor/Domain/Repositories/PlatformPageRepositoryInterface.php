<?php

namespace App\Modules\V1\Editor\Domain\Repositories;

use App\Modules\V1\Editor\Domain\Models\PlatformPage;
use Illuminate\Support\Collection;

interface PlatformPageRepositoryInterface
{
    public function listByPlatform(int $platformId): Collection;

    public function findOrFail(int $id): PlatformPage;

    public function create(array $attributes): PlatformPage;

    public function update(PlatformPage $page, array $attributes): PlatformPage;

    public function delete(PlatformPage $page): void;
}
