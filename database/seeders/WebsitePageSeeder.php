<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsitePageSeeder extends Seeder
{
    public function run(): void
    {
        foreach (WebsiteBlueprint::pages() as $pageKey => $pageData) {
            $existing = DB::table('pages')->where('key', $pageKey)->first(['id']);

            if ($existing) {
                DB::table('pages')->where('id', $existing->id)->update([
                    'position' => $pageData['position'],
                    'updated_at' => now(),
                ]);
                $pageId = $existing->id;
            } else {
                $pageId = DB::table('pages')->insertGetId([
                    'key' => $pageKey,
                    'position' => $pageData['position'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            foreach ($pageData['translations'] as $locale => $title) {
                DB::table('page_translations')->updateOrInsert(
                    ['page_id' => $pageId, 'locale' => $locale],
                    ['title' => $title]
                );
            }
        }
    }
}
