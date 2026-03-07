<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteInitialContentSeeder extends Seeder
{
    public function run(): void
    {
        $platformId = DB::table('platforms')->where('domain', 'demo.local')->value('id');

        if (! $platformId) {
            return;
        }

        foreach (WebsiteBlueprint::initialContent() as $scope => $content) {
            [$pageKey, $sectionKey] = explode('.', $scope);

            $platformSectionId = $this->findPlatformSectionId($platformId, $pageKey, $sectionKey);
            if (! $platformSectionId) {
                continue;
            }

            $structureMap = $this->sectionStructureMap($pageKey, $sectionKey);

            foreach ($content as $fieldKey => $value) {
                if (! isset($structureMap[$fieldKey])) {
                    continue;
                }

                $structure = $structureMap[$fieldKey];

                if ($structure->is_array && is_array($value) && array_is_list($value)) {
                    $this->seedRepeatedGroupValues($platformSectionId, $structureMap, $value);
                    continue;
                }

                if (is_array($value)) {
                    $this->upsertTranslatedValue(
                        $platformSectionId,
                        $structure->id,
                        0,
                        $structure->position,
                        $value
                    );
                }
            }
        }
    }

    private function seedRepeatedGroupValues(int $platformSectionId, array $structureMap, array $groupItems): void
    {
        foreach ($groupItems as $groupIndex => $itemFields) {
            foreach ($itemFields as $childKey => $translations) {
                if (! isset($structureMap[$childKey])) {
                    continue;
                }

                $childStructure = $structureMap[$childKey];
                $this->upsertTranslatedValue(
                    $platformSectionId,
                    $childStructure->id,
                    $groupIndex,
                    $childStructure->position,
                    $translations
                );
            }
        }
    }

    private function upsertTranslatedValue(
        int $platformSectionId,
        int $structureId,
        int $groupIndex,
        int $position,
        array $translations
    ): void {
        $existing = DB::table('section_values')
            ->where('platform_section_id', $platformSectionId)
            ->where('structure_id', $structureId)
            ->where('group_index', $groupIndex)
            ->where('position', $position)
            ->first(['id']);

        if ($existing) {
            $sectionValueId = $existing->id;
        } else {
            $sectionValueId = DB::table('section_values')->insertGetId([
                'platform_section_id' => $platformSectionId,
                'structure_id' => $structureId,
                'group_index' => $groupIndex,
                'position' => $position,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($translations as $locale => $content) {
            DB::table('section_value_translations')->updateOrInsert(
                ['section_value_id' => $sectionValueId, 'locale' => $locale],
                ['content' => $content]
            );
        }
    }

    private function findPlatformSectionId(int $platformId, string $pageKey, string $sectionKey): ?int
    {
        return DB::table('platform_sections')
            ->join('platform_pages', 'platform_pages.id', '=', 'platform_sections.platform_page_id')
            ->join('pages', 'pages.id', '=', 'platform_pages.page_id')
            ->join('sections', 'sections.id', '=', 'platform_sections.section_id')
            ->where('platform_pages.platform_id', $platformId)
            ->where('pages.key', $pageKey)
            ->where('sections.key', $sectionKey)
            ->value('platform_sections.id');
    }

    private function sectionStructureMap(string $pageKey, string $sectionKey): array
    {
        $structures = DB::table('structures')
            ->join('sections', 'sections.id', '=', 'structures.section_id')
            ->join('pages', 'pages.id', '=', 'sections.page_id')
            ->where('pages.key', $pageKey)
            ->where('sections.key', $sectionKey)
            ->select('structures.id', 'structures.key', 'structures.is_array', 'structures.position')
            ->get();

        $map = [];
        foreach ($structures as $structure) {
            $map[$structure->key] = $structure;
        }

        return $map;
    }
}
