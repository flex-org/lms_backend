<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteSectionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (WebsiteBlueprint::sections() as $pageKey => $sections) {
            $page = DB::table('pages')->where('key', $pageKey)->first(['id']);

            if (! $page) {
                continue;
            }

            foreach ($sections as $sectionKey => $sectionData) {
                $existing = DB::table('sections')
                    ->where('page_id', $page->id)
                    ->where('key', $sectionKey)
                    ->first(['id']);

                if ($existing) {
                    DB::table('sections')->where('id', $existing->id)->update([
                        'position' => $sectionData['position'],
                        'updated_at' => now(),
                    ]);
                    $sectionId = $existing->id;
                } else {
                    $sectionId = DB::table('sections')->insertGetId([
                        'page_id' => $page->id,
                        'key' => $sectionKey,
                        'position' => $sectionData['position'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                foreach ($sectionData['translations'] as $locale => $title) {
                    DB::table('section_translations')->updateOrInsert(
                        ['section_id' => $sectionId, 'locale' => $locale],
                        ['title' => $title]
                    );
                }
            }
        }
    }
}
