<?php

namespace App\Modules\V1\Editor\Domain\Repositories;

use App\Modules\V1\Editor\Domain\Models\PlatformSection;
use Illuminate\Support\Collection;

interface PlatformSectionRepositoryInterface
{
    public function listByPlatformPage(int $platformPageId): Collection;

    public function findOrFail(int $id): PlatformSection;

    public function create(array $attributes): PlatformSection;

    public function update(PlatformSection $section, array $attributes): PlatformSection;

    public function delete(PlatformSection $section): void;

    public function reorder(int $platformPageId, array $orderedIds): void;
}
