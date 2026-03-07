<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsitePlatformSeeder extends Seeder
{
    public function run(): void
    {
        $platformId = $this->ensureDemoPlatform();

        foreach (WebsiteBlueprint::pages() as $pageKey => $pageData) {
            $page = DB::table('pages')->where('key', $pageKey)->first(['id']);
            if (! $page) {
                continue;
            }

            $platformPageId = $this->upsertPlatformPage($platformId, $page->id);

            foreach (WebsiteBlueprint::sections()[$pageKey] as $sectionKey => $sectionData) {
                $section = DB::table('sections')
                    ->where('page_id', $page->id)
                    ->where('key', $sectionKey)
                    ->first(['id']);

                if (! $section) {
                    continue;
                }

                DB::table('platform_sections')->updateOrInsert(
                    ['platform_page_id' => $platformPageId, 'section_id' => $section->id],
                    ['active' => true, 'position' => $sectionData['position']]
                );
            }
        }
    }

    private function ensureDemoPlatform(): int
    {
        $themeId = DB::table('themes')->value('id') ?? 1;

        $existing = DB::table('platforms')->where('domain', 'demo.local')->first(['id']);
        if ($existing) {
            DB::table('platforms')->where('id', $existing->id)->update([
                'name' => 'Demo Platform',
                'status' => 'active',
                'updated_at' => now(),
            ]);

            return $existing->id;
        }

        return DB::table('platforms')->insertGetId([
            'theme_id' => $themeId,
            'domain' => 'demo.local',
            'storage' => 10240,
            'capacity' => 1000,
            'has_mobile_app' => false,
            'cost' => 0,
            'status' => 'active',
            'name' => 'Demo Platform',
            'about' => 'Demo platform for website content editor seed data.',
            'key_words' => json_encode(['lms', 'demo', 'content-editor']),
            'started_at' => now()->toDateString(),
            'renew_at' => now()->addYear()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function upsertPlatformPage(int $platformId, int $pageId): int
    {
        $existing = DB::table('platform_pages')
            ->where('platform_id', $platformId)
            ->where('page_id', $pageId)
            ->first(['id']);

        if ($existing) {
            DB::table('platform_pages')->where('id', $existing->id)->update([
                'active' => true,
                'updated_at' => now(),
            ]);

            return $existing->id;
        }

        return DB::table('platform_pages')->insertGetId([
            'platform_id' => $platformId,
            'page_id' => $pageId,
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
