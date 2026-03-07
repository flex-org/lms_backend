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
        $pageName = $page->translate('en')?->name;
        $sectionName = $section->translate('en')?->name;
        $structureName = $structure->name;

        $key = $pageName . '.' . $sectionName . '.' . $structureName;

        return $this->defaults()[$key] ?? [
            'en' => null,
            'ar' => null,
        ];
    }

    private function defaults(): array
    {
        return [
            // ---- HOME > Hero ----
            'Home.Hero.title' => [
                'en' => 'Mr. Mohamed Ahmed',
                'ar' => 'أ. محمد أحمد',
            ],
            'Home.Hero.subtitle' => [
                'en' => 'Learn everything, become anything',
                'ar' => 'تعلم كل شيء، كن أي شيء',
            ],
            'Home.Hero.bio' => [
                'en' => 'Hi I\'m Mr Mohamed Ahmed. I\'m a teacher, a leader, and a creator. I believe in the power of education to transform lives.',
                'ar' => 'مرحباً أنا الأستاذ محمد أحمد. أنا معلم وقائد ومبدع. أؤمن بقوة التعليم في تغيير الحياة.',
            ],
            'Home.Hero.image' => [
                'en' => null,
                'ar' => null,
            ],
            'Home.Hero.stats' => [
                'en' => json_encode([
                    ['value' => '40+', 'label' => 'Students'],
                    ['value' => '120+', 'label' => 'Courses'],
                    ['value' => '300+', 'label' => 'Lessons'],
                ]),
                'ar' => json_encode([
                    ['value' => '40+', 'label' => 'طالب'],
                    ['value' => '120+', 'label' => 'كورس'],
                    ['value' => '300+', 'label' => 'درس'],
                ]),
            ],

            // ---- HOME > Why Us ----
            'Home.Why Us.title' => [
                'en' => 'Why Learners Trust Us',
                'ar' => 'لماذا يثق بنا المتعلمون',
            ],
            'Home.Why Us.items' => [
                'en' => json_encode([
                    ['title' => 'Expert Instructors', 'description' => 'Learn from experienced and qualified instructors.'],
                    ['title' => 'Flexible Learning', 'description' => 'Study at your own pace, anytime, anywhere.'],
                    ['title' => 'Comprehensive Content', 'description' => 'Courses covering everything you need to succeed.'],
                ]),
                'ar' => json_encode([
                    ['title' => 'مدرسون خبراء', 'description' => 'تعلم من مدرسين ذوي خبرة ومؤهلين.'],
                    ['title' => 'تعلم مرن', 'description' => 'ادرس بالسرعة التي تناسبك، في أي وقت ومن أي مكان.'],
                    ['title' => 'محتوى شامل', 'description' => 'كورسات تغطي كل ما تحتاجه للنجاح.'],
                ]),
            ],

            // ---- HOME > CTA Banner ----
            'Home.CTA Banner.title' => [
                'en' => 'Start Your Learning Journey',
                'ar' => 'ابدأ رحلة التعلم',
            ],
            'Home.CTA Banner.subtitle' => [
                'en' => 'Join now and explore our courses',
                'ar' => 'انضم الآن واستكشف كورساتنا',
            ],
            'Home.CTA Banner.image' => [
                'en' => null,
                'ar' => null,
            ],

            // ---- HOME > Testimonials ----
            'Home.Testimonials.title' => [
                'en' => 'What Our Students Say',
                'ar' => 'ماذا يقول طلابنا',
            ],
            'Home.Testimonials.items' => [
                'en' => json_encode([
                    ['quote' => 'The graduation thesis is the final task for students.', 'author_name' => 'Ahmed Ali', 'author_role' => 'Student'],
                    ['quote' => 'An amazing experience that helped me improve my skills.', 'author_name' => 'Sara Mohamed', 'author_role' => 'Student'],
                ]),
                'ar' => json_encode([
                    ['quote' => 'أطروحة التخرج هي المهمة النهائية للطلاب.', 'author_name' => 'أحمد علي', 'author_role' => 'طالب'],
                    ['quote' => 'تجربة رائعة ساعدتني على تحسين مهاراتي.', 'author_name' => 'سارة محمد', 'author_role' => 'طالبة'],
                ]),
            ],

            // ---- HOME > FAQ ----
            'Home.FAQ.title' => [
                'en' => 'Frequently Asked Questions',
                'ar' => 'الأسئلة الشائعة',
            ],
            'Home.FAQ.items' => [
                'en' => json_encode([
                    ['question' => 'Can I switch plans later?', 'answer' => 'Yes, you can upgrade or downgrade your plan at any time.'],
                    ['question' => 'Do you offer a free trial?', 'answer' => 'Yes, we offer a free trial period for new users.'],
                    ['question' => 'What if I need to cancel the subscription?', 'answer' => 'You can cancel your subscription at any time from your account settings.'],
                    ['question' => 'Is billing monthly or yearly?', 'answer' => 'We offer both monthly and yearly billing options.'],
                ]),
                'ar' => json_encode([
                    ['question' => 'هل يمكنني تغيير الخطة لاحقاً؟', 'answer' => 'نعم، يمكنك ترقية أو تخفيض خطتك في أي وقت.'],
                    ['question' => 'هل تقدمون فترة تجريبية مجانية؟', 'answer' => 'نعم، نقدم فترة تجريبية مجانية للمستخدمين الجدد.'],
                    ['question' => 'ماذا لو أردت إلغاء الاشتراك؟', 'answer' => 'يمكنك إلغاء اشتراكك في أي وقت من إعدادات حسابك.'],
                    ['question' => 'هل الفوترة شهرية أم سنوية؟', 'answer' => 'نقدم خيارات فوترة شهرية وسنوية.'],
                ]),
            ],

            // ---- CATEGORIES > Page Header ----
            'Categories.Page Header.title' => [
                'en' => 'Categories',
                'ar' => 'الأقسام',
            ],
            'Categories.Page Header.subtitle' => [
                'en' => 'Browse our categories',
                'ar' => 'تصفح أقسامنا',
            ],
            'Categories.Page Header.background_image' => [
                'en' => null,
                'ar' => null,
            ],

            // ---- COURSES > Page Header ----
            'Courses.Page Header.title' => [
                'en' => 'Our Courses',
                'ar' => 'كورساتنا',
            ],
            'Courses.Page Header.subtitle' => [
                'en' => 'Explore our courses',
                'ar' => 'استكشف كورساتنا',
            ],
            'Courses.Page Header.background_image' => [
                'en' => null,
                'ar' => null,
            ],

            // ---- SUBSCRIPTION > Page Header ----
            'Subscription.Page Header.title' => [
                'en' => 'Ready to Start?',
                'ar' => 'مستعد للبدء؟',
            ],
            'Subscription.Page Header.subtitle' => [
                'en' => 'Select your Subscription',
                'ar' => 'اختر اشتراكك',
            ],

            // ---- SUBSCRIPTION > Plan Card ----
            'Subscription.Plan Card.plan_name' => [
                'en' => 'EduPlatform All-Access',
                'ar' => 'الوصول الكامل',
            ],
            'Subscription.Plan Card.plan_tagline' => [
                'en' => 'Best value for dedicated learners',
                'ar' => 'أفضل قيمة للمتعلمين المتفانين',
            ],
            'Subscription.Plan Card.features' => [
                'en' => json_encode([
                    'Unlock all Courses',
                    'Unlimited sessions',
                    'All Files & Quizzes',
                ]),
                'ar' => json_encode([
                    'فتح جميع الكورسات',
                    'جلسات غير محدودة',
                    'جميع الملفات والاختبارات',
                ]),
            ],

            'Subscription.Plan Card.security_note' => [
                'en' => 'All transactions are secure and encrypted',
                'ar' => 'جميع المعاملات آمنة ومشفرة',
            ],
            'Subscription.Plan Card.description' => [
                'en' => 'Unlock your full potential with unlimited access to our entire library. One simple subscription provides everything you need to master new skills, from expert-led courses to hands-on practice sessions.',
                'ar' => 'اطلق إمكانياتك الكاملة مع وصول غير محدود لمكتبتنا بالكامل. اشتراك واحد بسيط يوفر لك كل ما تحتاجه لإتقان مهارات جديدة.',
            ],

            // ---- SUBSCRIPTION > FAQ ----
            'Subscription.FAQ.title' => [
                'en' => 'Frequently Asked Questions',
                'ar' => 'الأسئلة الشائعة',
            ],
            'Subscription.FAQ.items' => [
                'en' => json_encode([
                    ['question' => 'Can I switch plans later?', 'answer' => 'Yes, you can upgrade or downgrade your plan at any time.'],
                    ['question' => 'Do you offer a free trial?', 'answer' => 'Yes, we offer a free trial period for new users.'],
                    ['question' => 'What if I need to cancel the subscription?', 'answer' => 'You can cancel your subscription at any time from your account settings.'],
                    ['question' => 'Is billing monthly or yearly?', 'answer' => 'We offer both monthly and yearly billing options.'],
                    ['question' => 'What\'s included in the Customized plan?', 'answer' => 'The customized plan includes all features tailored to your specific needs.'],
                ]),
                'ar' => json_encode([
                    ['question' => 'هل يمكنني تغيير الخطة لاحقاً؟', 'answer' => 'نعم، يمكنك ترقية أو تخفيض خطتك في أي وقت.'],
                    ['question' => 'هل تقدمون فترة تجريبية مجانية؟', 'answer' => 'نعم، نقدم فترة تجريبية مجانية للمستخدمين الجدد.'],
                    ['question' => 'ماذا لو أردت إلغاء الاشتراك؟', 'answer' => 'يمكنك إلغاء اشتراكك في أي وقت من إعدادات حسابك.'],
                    ['question' => 'هل الفوترة شهرية أم سنوية؟', 'answer' => 'نقدم خيارات فوترة شهرية وسنوية.'],
                    ['question' => 'ما المتضمن في الخطة المخصصة؟', 'answer' => 'الخطة المخصصة تشمل جميع المميزات المصممة خصيصاً لاحتياجاتك.'],
                ]),
            ],
        ];
    }
}
