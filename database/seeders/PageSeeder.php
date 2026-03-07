<?php

namespace Database\Seeders;

use App\Modules\V1\Editor\Domain\Models\Page;
use App\Modules\V1\Editor\Domain\Models\Section;
use App\Modules\V1\Editor\Domain\Models\Structure;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->pages() as $pageData) {
            $page = Page::create();
            foreach ($pageData['translations'] as $locale => $t) {
                $page->translateOrNew($locale)->name = $t['name'];
            }
            $page->save();

            foreach ($pageData['sections'] as $sectionData) {
                $section = Section::create(['page_id' => $page->id]);
                foreach ($sectionData['translations'] as $locale => $t) {
                    $section->translateOrNew($locale)->name = $t['name'];
                }
                $section->save();

                foreach ($sectionData['structures'] as $structData) {
                    $structure = Structure::create([
                        'section_id' => $section->id,
                        'type' => $structData['type'],
                        'name' => $structData['name'],
                        'is_array' => $structData['is_array'] ?? false,
                    ]);
                    foreach ($structData['translations'] as $locale => $t) {
                        $structure->translateOrNew($locale)->label = $t['label'] ?? null;
                        $structure->translateOrNew($locale)->placeholder = $t['placeholder'] ?? null;
                    }
                    $structure->save();
                }
            }
        }
    }

    private function pages(): array
    {
        return [
            $this->homePage(),
            $this->categoriesPage(),
            $this->coursesPage(),
            $this->subscriptionPage(),
        ];
    }

    private function homePage(): array
    {
        return [
            'translations' => [
                'en' => ['name' => 'Home'],
                'ar' => ['name' => 'الرئيسية'],
            ],
            'sections' => [
                [
                    'translations' => [
                        'en' => ['name' => 'Hero'],
                        'ar' => ['name' => 'البطل'],
                    ],
                    'structures' => [
                        ['type' => 'text', 'name' => 'title', 'translations' => [
                            'en' => ['label' => 'Title', 'placeholder' => 'title'],
                            'ar' => ['label' => 'العنوان', 'placeholder' => 'العنوان'],
                        ]],
                        ['type' => 'text', 'name' => 'subtitle', 'translations' => [
                            'en' => ['label' => 'Subtitle', 'placeholder' => 'Learn everything, become anything'],
                            'ar' => ['label' => 'العنوان الفرعي', 'placeholder' => 'تعلم كل شيء، كن أي شيء'],
                        ]],
                        ['type' => 'description', 'name' => 'bio', 'translations' => [
                            'en' => ['label' => 'Bio', 'placeholder' => 'Hi I\'m Mr Mohamed Ahmed...'],
                            'ar' => ['label' => 'نبذة', 'placeholder' => 'مرحبا أنا الأستاذ محمد أحمد...'],
                        ]],
                        ['type' => 'image', 'name' => 'image', 'translations' => [
                            'en' => ['label' => 'Hero Image'],
                            'ar' => ['label' => 'صورة البطل'],
                        ]],
                        ['type' => 'composite', 'name' => 'stats', 'is_array' => true, 'translations' => [
                            'en' => ['label' => 'Statistics', 'placeholder' => '{"value":"40+","label":"Students"}'],
                            'ar' => ['label' => 'الإحصائيات', 'placeholder' => '{"value":"40+","label":"طلاب"}'],
                        ]],
                    ],
                ],
                [
                    'translations' => [
                        'en' => ['name' => 'Why Us'],
                        'ar' => ['name' => 'لماذا نحن'],
                    ],
                    'structures' => [
                        ['type' => 'text', 'name' => 'title', 'translations' => [
                            'en' => ['label' => 'Section Title', 'placeholder' => 'Why Learners Trust Us'],
                            'ar' => ['label' => 'عنوان القسم', 'placeholder' => 'لماذا يثق بنا المتعلمون'],
                        ]],
                        ['type' => 'composite', 'name' => 'items', 'is_array' => true, 'translations' => [
                            'en' => ['label' => 'Reasons', 'placeholder' => '{"title":"...","description":"..."}'],
                            'ar' => ['label' => 'الأسباب', 'placeholder' => '{"title":"...","description":"..."}'],
                        ]],
                    ],
                ],
                [
                    'translations' => [
                        'en' => ['name' => 'CTA Banner'],
                        'ar' => ['name' => 'بانر الدعوة'],
                    ],
                    'structures' => [
                        ['type' => 'text', 'name' => 'title', 'translations' => [
                            'en' => ['label' => 'Title', 'placeholder' => 'Start Your Learning Journey'],
                            'ar' => ['label' => 'العنوان', 'placeholder' => 'ابدأ رحلة التعلم'],
                        ]],
                        ['type' => 'text', 'name' => 'subtitle', 'translations' => [
                            'en' => ['label' => 'Subtitle', 'placeholder' => 'Join now and explore'],
                            'ar' => ['label' => 'العنوان الفرعي', 'placeholder' => 'انضم الآن واستكشف'],
                        ]],
                        ['type' => 'image', 'name' => 'image', 'translations' => [
                            'en' => ['label' => 'Banner Image'],
                            'ar' => ['label' => 'صورة البانر'],
                        ]],
                    ],
                ],
                [
                    'translations' => [
                        'en' => ['name' => 'Testimonials'],
                        'ar' => ['name' => 'آراء الطلاب'],
                    ],
                    'structures' => [
                        ['type' => 'text', 'name' => 'title', 'translations' => [
                            'en' => ['label' => 'Section Title', 'placeholder' => 'What Our Students Say'],
                            'ar' => ['label' => 'عنوان القسم', 'placeholder' => 'ماذا يقول طلابنا'],
                        ]],
                        ['type' => 'composite', 'name' => 'items', 'is_array' => true, 'translations' => [
                            'en' => ['label' => 'Testimonials', 'placeholder' => '{"quote":"...","author_name":"...","author_role":"..."}'],
                            'ar' => ['label' => 'الآراء', 'placeholder' => '{"quote":"...","author_name":"...","author_role":"..."}'],
                        ]],
                    ],
                ],
                [
                    'translations' => [
                        'en' => ['name' => 'FAQ'],
                        'ar' => ['name' => 'الأسئلة الشائعة'],
                    ],
                    'structures' => [
                        ['type' => 'text', 'name' => 'title', 'translations' => [
                            'en' => ['label' => 'Section Title', 'placeholder' => 'Frequently Asked Questions'],
                            'ar' => ['label' => 'عنوان القسم', 'placeholder' => 'الأسئلة الشائعة'],
                        ]],
                        ['type' => 'composite', 'name' => 'items', 'is_array' => true, 'translations' => [
                            'en' => ['label' => 'Questions', 'placeholder' => '{"question":"...","answer":"..."}'],
                            'ar' => ['label' => 'الأسئلة', 'placeholder' => '{"question":"...","answer":"..."}'],
                        ]],
                    ],
                ],
            ],
        ];
    }

    private function categoriesPage(): array
    {
        return [
            'translations' => [
                'en' => ['name' => 'Categories'],
                'ar' => ['name' => 'الأقسام'],
            ],
            'sections' => [
                [
                    'translations' => [
                        'en' => ['name' => 'Page Header'],
                        'ar' => ['name' => 'ترويسة الصفحة'],
                    ],
                    'structures' => [
                        ['type' => 'text', 'name' => 'title', 'translations' => [
                            'en' => ['label' => 'Title', 'placeholder' => 'Categories'],
                            'ar' => ['label' => 'العنوان', 'placeholder' => 'الأقسام'],
                        ]],
                        ['type' => 'text', 'name' => 'subtitle', 'translations' => [
                            'en' => ['label' => 'Subtitle', 'placeholder' => 'Browse our categories'],
                            'ar' => ['label' => 'العنوان الفرعي', 'placeholder' => 'تصفح أقسامنا'],
                        ]],
                        ['type' => 'image', 'name' => 'background_image', 'translations' => [
                            'en' => ['label' => 'Background Image'],
                            'ar' => ['label' => 'صورة الخلفية'],
                        ]],
                    ],
                ],
            ],
        ];
    }

    private function coursesPage(): array
    {
        return [
            'translations' => [
                'en' => ['name' => 'Courses'],
                'ar' => ['name' => 'الكورسات'],
            ],
            'sections' => [
                [
                    'translations' => [
                        'en' => ['name' => 'Page Header'],
                        'ar' => ['name' => 'ترويسة الصفحة'],
                    ],
                    'structures' => [
                        ['type' => 'text', 'name' => 'title', 'translations' => [
                            'en' => ['label' => 'Title', 'placeholder' => 'Our Courses'],
                            'ar' => ['label' => 'العنوان', 'placeholder' => 'كورساتنا'],
                        ]],
                        ['type' => 'text', 'name' => 'subtitle', 'translations' => [
                            'en' => ['label' => 'Subtitle', 'placeholder' => 'Explore our courses'],
                            'ar' => ['label' => 'العنوان الفرعي', 'placeholder' => 'استكشف كورساتنا'],
                        ]],
                        ['type' => 'image', 'name' => 'background_image', 'translations' => [
                            'en' => ['label' => 'Background Image'],
                            'ar' => ['label' => 'صورة الخلفية'],
                        ]],
                    ],
                ],
            ],
        ];
    }

    private function subscriptionPage(): array
    {
        return [
            'translations' => [
                'en' => ['name' => 'Subscription'],
                'ar' => ['name' => 'الاشتراك'],
            ],
            'sections' => [
                [
                    'translations' => [
                        'en' => ['name' => 'Page Header'],
                        'ar' => ['name' => 'ترويسة الصفحة'],
                    ],
                    'structures' => [
                        ['type' => 'text', 'name' => 'title', 'translations' => [
                            'en' => ['label' => 'Title', 'placeholder' => 'Ready to Start?'],
                            'ar' => ['label' => 'العنوان', 'placeholder' => 'مستعد للبدء؟'],
                        ]],
                        ['type' => 'text', 'name' => 'subtitle', 'translations' => [
                            'en' => ['label' => 'Subtitle', 'placeholder' => 'Select your Subscription'],
                            'ar' => ['label' => 'العنوان الفرعي', 'placeholder' => 'اختر اشتراكك'],
                        ]],
                    ],
                ],
                [
                    'translations' => [
                        'en' => ['name' => 'Plan Card'],
                        'ar' => ['name' => 'بطاقة الخطة'],
                    ],
                    'structures' => [
                        ['type' => 'text', 'name' => 'plan_name', 'translations' => [
                            'en' => ['label' => 'Plan Name', 'placeholder' => 'All-Access'],
                            'ar' => ['label' => 'اسم الخطة', 'placeholder' => 'الوصول الكامل'],
                        ]],
                        ['type' => 'text', 'name' => 'plan_tagline', 'translations' => [
                            'en' => ['label' => 'Tagline', 'placeholder' => 'Best value for dedicated learners'],
                            'ar' => ['label' => 'الوصف المختصر', 'placeholder' => 'أفضل قيمة للمتعلمين المتفانين'],
                        ]],
                        ['type' => 'text', 'name' => 'features', 'is_array' => true, 'translations' => [
                            'en' => ['label' => 'Features', 'placeholder' => 'Unlock all Courses'],
                            'ar' => ['label' => 'المميزات', 'placeholder' => 'فتح جميع الكورسات'],
                        ]],
                        ['type' => 'text', 'name' => 'security_note', 'translations' => [
                            'en' => ['label' => 'Security Note', 'placeholder' => 'All transactions are secure and encrypted'],
                            'ar' => ['label' => 'ملاحظة أمان', 'placeholder' => 'جميع المعاملات آمنة ومشفرة'],
                        ]],
                        ['type' => 'description', 'name' => 'description', 'translations' => [
                            'en' => ['label' => 'Description', 'placeholder' => 'Unlock your full potential with unlimited access to our entire library.'],
                            'ar' => ['label' => 'الوصف', 'placeholder' => 'اطلق إمكانياتك الكاملة مع وصول غير محدود لمكتبتنا بالكامل.'],
                        ]],
                    ],
                ],
                [
                    'translations' => [
                        'en' => ['name' => 'FAQ'],
                        'ar' => ['name' => 'الأسئلة الشائعة'],
                    ],
                    'structures' => [
                        ['type' => 'text', 'name' => 'title', 'translations' => [
                            'en' => ['label' => 'Section Title', 'placeholder' => 'Frequently Asked Questions'],
                            'ar' => ['label' => 'عنوان القسم', 'placeholder' => 'الأسئلة الشائعة'],
                        ]],
                        ['type' => 'composite', 'name' => 'items', 'is_array' => true, 'translations' => [
                            'en' => ['label' => 'Questions', 'placeholder' => '{"question":"...","answer":"..."}'],
                            'ar' => ['label' => 'الأسئلة', 'placeholder' => '{"question":"...","answer":"..."}'],
                        ]],
                    ],
                ],
            ],
        ];
    }
}
