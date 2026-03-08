<?php

namespace App\Modules\V1\Editor\Application\UseCases;

use App\Modules\V1\Editor\Domain\Models\Page;
use App\Modules\V1\Editor\Domain\Models\PlatformPage;
use App\Modules\V1\Editor\Domain\Models\PlatformSection;
use App\Modules\V1\Editor\Domain\Models\Values;
use App\Modules\V1\Platforms\Domain\Models\Platform;

final class InitializePlatformBuilderUseCase
{
    public function execute(Platform $platform): void
    {
        $pages = Page::with('sections.structures')->get();

        foreach ($pages as $page) {
            $platformPage = PlatformPage::create([
                'platform_id' => $platform->id,
                'page_id' => $page->id,
                'active' => true,
            ]);

            $position = 0;

            foreach ($page->sections as $section) {
                $platformSection = PlatformSection::create([
                    'platform_page_id' => $platformPage->id,
                    'section_id' => $section->id,
                    'active' => true,
                    'position' => $position++,
                ]);

                foreach ($section->structures as $structure) {
                    $defaults = $this->defaultValueFor($page, $section, $structure);

                    $value = Values::create([
                        'platform_section_id' => $platformSection->id,
                        'structure_id' => $structure->id,
                    ]);

                    foreach (['en', 'ar'] as $locale) {
                        $value->translateOrNew($locale)->value = $defaults[$locale] ?? null;
                    }
                    $value->save();
                }
            }
        }
    }

    private function defaultValueFor($page, $section, $structure): array
    {
        $sectionName = $section->translate('en')?->name;
        $key = $page->key . '.' . $sectionName . '.' . $structure->name;

        return $this->defaults()[$key] ?? [
            'en' => null,
            'ar' => null,
        ];
    }

    private function defaults(): array
    {
        return [
            // ---- HOME > Hero ----
            'home.Hero.title' => [
                'en' => 'Mr. Mohamed Ahmed',
                'ar' => 'أ. محمد أحمد',
            ],
            'home.Hero.subtitle' => [
                'en' => 'Learn everything, become anything',
                'ar' => 'تعلم كل شيء، كن أي شيء',
            ],
            'home.Hero.bio' => [
                'en' => 'Hi I\'m Mr Mohamed Ahmed. I\'m a teacher, a leader, and a creator. I believe in the power of education to transform lives.',
                'ar' => 'مرحباً أنا الأستاذ محمد أحمد. أنا معلم وقائد ومبدع. أؤمن بقوة التعليم في تغيير الحياة.',
            ],
            'home.Hero.image' => [
                'en' => null,
                'ar' => null,
            ],
            'home.Hero.stats' => [
                'en' => [
                    ['value' => '40+', 'label' => 'Students'],
                    ['value' => '120+', 'label' => 'Courses'],
                    ['value' => '300+', 'label' => 'Lessons'],
                ],
                'ar' => [
                    ['value' => '40+', 'label' => 'طالب'],
                    ['value' => '120+', 'label' => 'كورس'],
                    ['value' => '300+', 'label' => 'درس'],
                ],
            ],

            // ---- HOME > Why Us ----
            'home.Why Us.title' => [
                'en' => 'Why Learners Trust Us',
                'ar' => 'لماذا يثق بنا المتعلمون',
            ],
            'home.Why Us.items' => [
                'en' => [
                    ['title' => 'Expert Instructors', 'description' => 'Learn from experienced and qualified instructors.'],
                    ['title' => 'Flexible Learning', 'description' => 'Study at your own pace, anytime, anywhere.'],
                    ['title' => 'Comprehensive Content', 'description' => 'Courses covering everything you need to succeed.'],
                ],
                'ar' => [
                    ['title' => 'مدرسون خبراء', 'description' => 'تعلم من مدرسين ذوي خبرة ومؤهلين.'],
                    ['title' => 'تعلم مرن', 'description' => 'ادرس بالسرعة التي تناسبك، في أي وقت ومن أي مكان.'],
                    ['title' => 'محتوى شامل', 'description' => 'كورسات تغطي كل ما تحتاجه للنجاح.'],
                ],
            ],

            // ---- HOME > CTA Banner ----
            'home.CTA Banner.title' => [
                'en' => 'Start Your Learning Journey',
                'ar' => 'ابدأ رحلة التعلم',
            ],
            'home.CTA Banner.subtitle' => [
                'en' => 'Join now and explore our courses',
                'ar' => 'انضم الآن واستكشف كورساتنا',
            ],
            'home.CTA Banner.image' => [
                'en' => null,
                'ar' => null,
            ],

            // ---- HOME > Testimonials ----
            'home.Testimonials.title' => [
                'en' => 'What Our Students Say',
                'ar' => 'ماذا يقول طلابنا',
            ],
            'home.Testimonials.items' => [
                'en' => [
                    ['quote' => 'The graduation thesis is the final task for students.', 'author_name' => 'Ahmed Ali', 'author_role' => 'Student'],
                    ['quote' => 'An amazing experience that helped me improve my skills.', 'author_name' => 'Sara Mohamed', 'author_role' => 'Student'],
                ],
                'ar' => [
                    ['quote' => 'أطروحة التخرج هي المهمة النهائية للطلاب.', 'author_name' => 'أحمد علي', 'author_role' => 'طالب'],
                    ['quote' => 'تجربة رائعة ساعدتني على تحسين مهاراتي.', 'author_name' => 'سارة محمد', 'author_role' => 'طالبة'],
                ],
            ],

            // ---- HOME > FAQ ----
            'home.FAQ.title' => [
                'en' => 'Frequently Asked Questions',
                'ar' => 'الأسئلة الشائعة',
            ],
            'home.FAQ.items' => [
                'en' => [
                    ['question' => 'Can I switch plans later?', 'answer' => 'Yes, you can upgrade or downgrade your plan at any time.'],
                    ['question' => 'Do you offer a free trial?', 'answer' => 'Yes, we offer a free trial period for new users.'],
                    ['question' => 'What if I need to cancel the subscription?', 'answer' => 'You can cancel your subscription at any time from your account settings.'],
                    ['question' => 'Is billing monthly or yearly?', 'answer' => 'We offer both monthly and yearly billing options.'],
                ],
                'ar' => [
                    ['question' => 'هل يمكنني تغيير الخطة لاحقاً؟', 'answer' => 'نعم، يمكنك ترقية أو تخفيض خطتك في أي وقت.'],
                    ['question' => 'هل تقدمون فترة تجريبية مجانية؟', 'answer' => 'نعم، نقدم فترة تجريبية مجانية للمستخدمين الجدد.'],
                    ['question' => 'ماذا لو أردت إلغاء الاشتراك؟', 'answer' => 'يمكنك إلغاء اشتراكك في أي وقت من إعدادات حسابك.'],
                    ['question' => 'هل الفوترة شهرية أم سنوية؟', 'answer' => 'نقدم خيارات فوترة شهرية وسنوية.'],
                ],
            ],

            // ---- CATEGORIES > Page Header ----
            'categories.Page Header.title' => [
                'en' => 'Categories',
                'ar' => 'الأقسام',
            ],
            'categories.Page Header.subtitle' => [
                'en' => 'Browse our categories',
                'ar' => 'تصفح أقسامنا',
            ],
            'categories.Page Header.background_image' => [
                'en' => null,
                'ar' => null,
            ],

            // ---- COURSES > Page Header ----
            'courses.Page Header.title' => [
                'en' => 'Our Courses',
                'ar' => 'كورساتنا',
            ],
            'courses.Page Header.subtitle' => [
                'en' => 'Explore our courses',
                'ar' => 'استكشف كورساتنا',
            ],
            'courses.Page Header.background_image' => [
                'en' => null,
                'ar' => null,
            ],

            // ---- SUBSCRIPTION > Page Header ----
            'subscription.Page Header.title' => [
                'en' => 'Ready to Start?',
                'ar' => 'مستعد للبدء؟',
            ],
            'subscription.Page Header.subtitle' => [
                'en' => 'Select your Subscription',
                'ar' => 'اختر اشتراكك',
            ],

            // ---- SUBSCRIPTION > Plan Card ----
            'subscription.Plan Card.plan_name' => [
                'en' => 'EduPlatform All-Access',
                'ar' => 'الوصول الكامل',
            ],
            'subscription.Plan Card.plan_tagline' => [
                'en' => 'Best value for dedicated learners',
                'ar' => 'أفضل قيمة للمتعلمين المتفانين',
            ],
            'subscription.Plan Card.features' => [
                'en' => [
                    'Unlock all Courses',
                    'Unlimited sessions',
                    'All Files & Quizzes',
                ],
                'ar' => [
                    'فتح جميع الكورسات',
                    'جلسات غير محدودة',
                    'جميع الملفات والاختبارات',
                ],
            ],

            'subscription.Plan Card.security_note' => [
                'en' => 'All transactions are secure and encrypted',
                'ar' => 'جميع المعاملات آمنة ومشفرة',
            ],
            'subscription.Plan Card.description' => [
                'en' => 'Unlock your full potential with unlimited access to our entire library. One simple subscription provides everything you need to master new skills, from expert-led courses to hands-on practice sessions.',
                'ar' => 'اطلق إمكانياتك الكاملة مع وصول غير محدود لمكتبتنا بالكامل. اشتراك واحد بسيط يوفر لك كل ما تحتاجه لإتقان مهارات جديدة.',
            ],

            // ---- SUBSCRIPTION > FAQ ----
            'subscription.FAQ.title' => [
                'en' => 'Frequently Asked Questions',
                'ar' => 'الأسئلة الشائعة',
            ],
            'subscription.FAQ.items' => [
                'en' => [
                    ['question' => 'Can I switch plans later?', 'answer' => 'Yes, you can upgrade or downgrade your plan at any time.'],
                    ['question' => 'Do you offer a free trial?', 'answer' => 'Yes, we offer a free trial period for new users.'],
                    ['question' => 'What if I need to cancel the subscription?', 'answer' => 'You can cancel your subscription at any time from your account settings.'],
                    ['question' => 'Is billing monthly or yearly?', 'answer' => 'We offer both monthly and yearly billing options.'],
                    ['question' => 'What\'s included in the Customized plan?', 'answer' => 'The customized plan includes all features tailored to your specific needs.'],
                ],
                'ar' => [
                    ['question' => 'هل يمكنني تغيير الخطة لاحقاً؟', 'answer' => 'نعم، يمكنك ترقية أو تخفيض خطتك في أي وقت.'],
                    ['question' => 'هل تقدمون فترة تجريبية مجانية؟', 'answer' => 'نعم، نقدم فترة تجريبية مجانية للمستخدمين الجدد.'],
                    ['question' => 'ماذا لو أردت إلغاء الاشتراك؟', 'answer' => 'يمكنك إلغاء اشتراكك في أي وقت من إعدادات حسابك.'],
                    ['question' => 'هل الفوترة شهرية أم سنوية؟', 'answer' => 'نقدم خيارات فوترة شهرية وسنوية.'],
                    ['question' => 'ما المتضمن في الخطة المخصصة؟', 'answer' => 'الخطة المخصصة تشمل جميع المميزات المصممة خصيصاً لاحتياجاتك.'],
                ],
            ],
        ];
    }
}
