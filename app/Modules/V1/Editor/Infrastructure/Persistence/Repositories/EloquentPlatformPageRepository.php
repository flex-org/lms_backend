<?php

namespace App\Modules\V1\Editor\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Editor\Domain\Models\PlatformPage;
use App\Modules\V1\Editor\Domain\Repositories\PlatformPageRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentPlatformPageRepository implements PlatformPageRepositoryInterface
{
    public function listByPlatform(int $platformId): Collection
    {
        return PlatformPage::where('platform_id', $platformId)
            ->with(['page', 'platformSections.section'])
            ->get();
    }

    public function findOrFail(int $id): PlatformPage
    {
        return PlatformPage::with(['page', 'platformSections.section'])->findOrFail($id);
    }

    public function create(array $attributes): PlatformPage
    {
        return PlatformPage::create($attributes);
    }

    public function update(PlatformPage $page, array $attributes): PlatformPage
    {
        $page->update($attributes);

        return $page->fresh(['page', 'platformSections.section']);
    }

    public function delete(PlatformPage $page): void
    {
        $page->delete();
    }
}
