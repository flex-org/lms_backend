<?php

namespace Database\Seeders;

use App\Modules\V1\Editor\Domain\Models\Page;
use App\Modules\V1\Editor\Domain\Models\Section;
use App\Modules\V1\Editor\Domain\Models\Structure;
use Illuminate\Database\Seeder;

class BuilderSchemaSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'key' => 'home',
                'translations' => [
                    'en' => ['name' => 'home'],
                    'ar' => ['name' => 'الرئيسية'],
                ],
            ],
            [
                'key' => 'categories',
                'translations' => [
                    'en' => ['name' => 'categories'],
                    'ar' => ['name' => 'التصنيفات'],
                ],
            ],
            [
                'key' => 'courses',
                'translations' => [
                    'en' => ['name' => 'courses'],
                    'ar' => ['name' => 'الكورسات'],
                ],
            ],
            [
                'key' => 'subscription',
                'translations' => [
                    'en' => ['name' => 'subscription'],
                    'ar' => ['name' => 'الاشتراك'],
                ],
            ],
        ];

        $sections = [
            'hero' => [
                'translations' => ['en' => 'hero', 'ar' => 'القسم الرئيسي'],
                'structures' => [
                    ['type' => 'text', 'name' => 'title', 'is_array' => false, 'label' => ['en' => 'Hero title', 'ar' => 'عنوان القسم الرئيسي'], 'placeholder' => ['en' => 'Write hero title', 'ar' => 'اكتب عنوان القسم الرئيسي']],
                    ['type' => 'description', 'name' => 'description', 'is_array' => false, 'label' => ['en' => 'Hero description', 'ar' => 'وصف القسم الرئيسي'], 'placeholder' => ['en' => 'Write short description', 'ar' => 'اكتب وصفًا مختصرًا']],
                    ['type' => 'image', 'name' => 'image', 'is_array' => false, 'label' => ['en' => 'Hero image', 'ar' => 'صورة القسم الرئيسي'], 'placeholder' => ['en' => 'Upload image', 'ar' => 'ارفع صورة']],
                    ['type' => 'text', 'name' => 'button_text', 'is_array' => false, 'label' => ['en' => 'CTA label', 'ar' => 'نص زر الإجراء'], 'placeholder' => ['en' => 'Discover courses', 'ar' => 'اكتشف الكورسات']],
                ],
            ],
            'faq' => [
                'translations' => ['en' => 'faq', 'ar' => 'الأسئلة الشائعة'],
                'structures' => [
                    ['type' => 'text', 'name' => 'title', 'is_array' => false, 'label' => ['en' => 'FAQ title', 'ar' => 'عنوان الأسئلة الشائعة'], 'placeholder' => ['en' => 'Frequently asked questions', 'ar' => 'الأسئلة الشائعة']],
                    ['type' => 'description', 'name' => 'description', 'is_array' => true, 'label' => ['en' => 'Question and answer', 'ar' => 'السؤال والإجابة'], 'placeholder' => ['en' => 'Write question and answer', 'ar' => 'اكتب السؤال والإجابة']],
                ],
            ],
        ];

        foreach ($pages as $pageData) {
            $page = $this->upsertPage($pageData['translations']);

            foreach ($sections as $sectionData) {
                $section = $this->upsertSection($page->id, $sectionData['translations']);

                foreach ($sectionData['structures'] as $structureData) {
                    $this->upsertStructure($section->id, $structureData);
                }
            }
        }
    }

    private function upsertPage(array $translations): Page
    {
        $page = Page::query()
            ->whereTranslation('name', $translations['en']['name'], 'en')
            ->first() ?? new Page();

        foreach ($translations as $locale => $fields) {
            $page->translateOrNew($locale)->name = $fields['name'];
        }

        $page->save();

        return $page;
    }

    private function upsertSection(int $pageId, array $translations): Section
    {
        $section = Section::query()
            ->where('page_id', $pageId)
            ->whereTranslation('name', $translations['en'], 'en')
            ->first() ?? new Section(['page_id' => $pageId]);

        $section->page_id = $pageId;

        foreach ($translations as $locale => $name) {
            $section->translateOrNew($locale)->name = $name;
        }

        $section->save();

        return $section;
    }

    private function upsertStructure(int $sectionId, array $structureData): void
    {
        $structure = Structure::updateOrCreate(
            [
                'section_id' => $sectionId,
                'name' => $structureData['name'],
            ],
            [
                'type' => $structureData['type'],
                'is_array' => $structureData['is_array'],
            ]
        );

        foreach ($structureData['label'] as $locale => $label) {
            $structure->translateOrNew($locale)->label = $label;
            $structure->translateOrNew($locale)->placeholder = $structureData['placeholder'][$locale] ?? null;
        }

        $structure->save();
    }
}
