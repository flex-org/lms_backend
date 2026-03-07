<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteStructureSeeder extends Seeder
{
    public function run(): void
    {
        foreach (WebsiteBlueprint::structures() as $scope => $structures) {
            [$pageKey, $sectionKey] = explode('.', $scope);

            $section = DB::table('sections')
                ->join('pages', 'pages.id', '=', 'sections.page_id')
                ->where('pages.key', $pageKey)
                ->where('sections.key', $sectionKey)
                ->select('sections.id')
                ->first();

            if (! $section) {
                continue;
            }

            $sectionStructureIds = [];

            foreach ($structures as $structureData) {
                $parentId = null;
                if (! empty($structureData['parent_key'])) {
                    $parentId = $sectionStructureIds[$structureData['parent_key']] ?? null;
                }

                $existing = DB::table('structures')
                    ->where('section_id', $section->id)
                    ->where('key', $structureData['key'])
                    ->first(['id']);

                $payload = [
                    'section_id' => $section->id,
                    'parent_id' => $parentId,
                    'key' => $structureData['key'],
                    'type' => $structureData['type'],
                    'is_array' => $structureData['is_array'] ?? false,
                    'position' => $structureData['position'] ?? 0,
                    'settings' => isset($structureData['settings']) ? json_encode($structureData['settings']) : null,
                    'updated_at' => now(),
                ];

                if ($existing) {
                    DB::table('structures')->where('id', $existing->id)->update($payload);
                    $structureId = $existing->id;
                } else {
                    $payload['created_at'] = now();
                    $structureId = DB::table('structures')->insertGetId($payload);
                }

                $sectionStructureIds[$structureData['key']] = $structureId;

                foreach ($structureData['translations'] as $locale => $label) {
                    DB::table('structure_translations')->updateOrInsert(
                        ['structure_id' => $structureId, 'locale' => $locale],
                        ['label' => $label, 'placeholder' => null]
                    );
                }
            }
        }
    }
}
