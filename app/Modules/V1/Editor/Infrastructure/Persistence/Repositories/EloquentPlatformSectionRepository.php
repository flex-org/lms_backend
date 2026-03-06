<?php

namespace App\Modules\V1\Editor\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Editor\Domain\Models\PlatformSection;
use App\Modules\V1\Editor\Domain\Repositories\PlatformSectionRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentPlatformSectionRepository implements PlatformSectionRepositoryInterface
{
    public function listByPlatformPage(int $platformPageId): Collection
    {
        return PlatformSection::where('platform_page_id', $platformPageId)
            ->with(['section.structures', 'sectionValues'])
            ->orderBy('position')
            ->get();
    }

    public function findOrFail(int $id): PlatformSection
    {
        return PlatformSection::with(['section.structures', 'sectionValues'])
            ->findOrFail($id);
    }

    public function create(array $attributes): PlatformSection
    {
        if (! isset($attributes['position'])) {
            $maxPosition = PlatformSection::where('platform_page_id', $attributes['platform_page_id'])
                ->max('position');

            $attributes['position'] = ($maxPosition ?? 0) + 1;
        }

        return PlatformSection::create($attributes);
    }

    public function update(PlatformSection $section, array $attributes): PlatformSection
    {
        $section->update($attributes);

        return $section->fresh(['section.structures', 'sectionValues']);
    }

    public function delete(PlatformSection $section): void
    {
        $section->delete();
    }

    public function reorder(int $platformPageId, array $orderedIds): void
    {
        foreach ($orderedIds as $position => $id) {
            PlatformSection::where('id', $id)
                ->where('platform_page_id', $platformPageId)
                ->update(['position' => $position + 1]);
        }
    }
}
