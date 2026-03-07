<?php

namespace Database\Seeders;

class WebsiteBlueprint
{
    public static function pages(): array
    {
        return [
            'home' => [
                'position' => 1,
                'translations' => ['en' => 'Home', 'ar' => 'الرئيسية'],
            ],
            'categories' => [
                'position' => 2,
                'translations' => ['en' => 'Categories', 'ar' => 'الفئات'],
            ],
            'courses' => [
                'position' => 3,
                'translations' => ['en' => 'Courses', 'ar' => 'الدورات'],
            ],
            'subscription' => [
                'position' => 4,
                'translations' => ['en' => 'Subscription', 'ar' => 'الاشتراك'],
            ],
        ];
    }

    public static function sections(): array
    {
        return [
            'home' => [
                'hero' => ['position' => 1, 'translations' => ['en' => 'Hero', 'ar' => 'البطولة']],
                'about_teacher' => ['position' => 2, 'translations' => ['en' => 'About Teacher', 'ar' => 'عن المعلم']],
                'trust_features' => ['position' => 3, 'translations' => ['en' => 'Trust Features', 'ar' => 'مزايا الثقة']],
                'cta_banner' => ['position' => 4, 'translations' => ['en' => 'CTA Banner', 'ar' => 'شريط الدعوة للإجراء']],
                'faq_contact' => ['position' => 5, 'translations' => ['en' => 'FAQ & Contact', 'ar' => 'الأسئلة والتواصل']],
            ],
            'categories' => [
                'page_hero' => ['position' => 1, 'translations' => ['en' => 'Page Hero', 'ar' => 'واجهة الصفحة']],
            ],
            'courses' => [
                'page_hero' => ['position' => 1, 'translations' => ['en' => 'Page Hero', 'ar' => 'واجهة الصفحة']],
            ],
            'subscription' => [
                'breadcrumb_header' => ['position' => 1, 'translations' => ['en' => 'Breadcrumb Header', 'ar' => 'رأس المسار']],
                'subscription_faq' => ['position' => 2, 'translations' => ['en' => 'Subscription FAQ', 'ar' => 'أسئلة الاشتراك']],
                'pricing_card' => ['position' => 3, 'translations' => ['en' => 'Pricing Card', 'ar' => 'بطاقة السعر']],
            ],
        ];
    }

    public static function structures(): array
    {
        return [
            'home.hero' => [
                ['key' => 'teacher_name', 'type' => 'text', 'position' => 1, 'translations' => ['en' => 'Teacher Name', 'ar' => 'اسم المعلم']],
                ['key' => 'headline', 'type' => 'text', 'position' => 2, 'translations' => ['en' => 'Headline', 'ar' => 'العنوان الرئيسي']],
                ['key' => 'subheadline', 'type' => 'textarea', 'position' => 3, 'translations' => ['en' => 'Subheadline', 'ar' => 'العنوان الفرعي']],
                ['key' => 'cta_text', 'type' => 'text', 'position' => 4, 'translations' => ['en' => 'CTA Text', 'ar' => 'نص الزر']],
                ['key' => 'cta_url', 'type' => 'url', 'position' => 5, 'translations' => ['en' => 'CTA URL', 'ar' => 'رابط الزر']],
                ['key' => 'hero_image', 'type' => 'image', 'position' => 6, 'translations' => ['en' => 'Hero Image', 'ar' => 'صورة الواجهة']],
                ['key' => 'stats', 'type' => 'group', 'is_array' => true, 'position' => 7, 'translations' => ['en' => 'Stats', 'ar' => 'الإحصائيات']],
                ['key' => 'stats_value', 'type' => 'text', 'parent_key' => 'stats', 'position' => 1, 'translations' => ['en' => 'Stat Value', 'ar' => 'قيمة الإحصائية']],
                ['key' => 'stats_label', 'type' => 'text', 'parent_key' => 'stats', 'position' => 2, 'translations' => ['en' => 'Stat Label', 'ar' => 'عنوان الإحصائية']],
            ],
            'home.about_teacher' => [
                ['key' => 'avatar', 'type' => 'image', 'position' => 1, 'translations' => ['en' => 'Avatar', 'ar' => 'الصورة الشخصية']],
                ['key' => 'title', 'type' => 'text', 'position' => 2, 'translations' => ['en' => 'Title', 'ar' => 'العنوان']],
                ['key' => 'description', 'type' => 'textarea', 'position' => 3, 'translations' => ['en' => 'Description', 'ar' => 'الوصف']],
            ],
            'home.trust_features' => [
                ['key' => 'title', 'type' => 'text', 'position' => 1, 'translations' => ['en' => 'Title', 'ar' => 'العنوان']],
                ['key' => 'subtitle', 'type' => 'textarea', 'position' => 2, 'translations' => ['en' => 'Subtitle', 'ar' => 'العنوان الفرعي']],
                ['key' => 'items', 'type' => 'group', 'is_array' => true, 'position' => 3, 'translations' => ['en' => 'Items', 'ar' => 'العناصر']],
                ['key' => 'item_title', 'type' => 'text', 'parent_key' => 'items', 'position' => 1, 'translations' => ['en' => 'Item Title', 'ar' => 'عنوان العنصر']],
                ['key' => 'item_description', 'type' => 'textarea', 'parent_key' => 'items', 'position' => 2, 'translations' => ['en' => 'Item Description', 'ar' => 'وصف العنصر']],
            ],
            'home.cta_banner' => [
                ['key' => 'title', 'type' => 'text', 'position' => 1, 'translations' => ['en' => 'Title', 'ar' => 'العنوان']],
                ['key' => 'description', 'type' => 'textarea', 'position' => 2, 'translations' => ['en' => 'Description', 'ar' => 'الوصف']],
                ['key' => 'cta_text', 'type' => 'text', 'position' => 3, 'translations' => ['en' => 'CTA Text', 'ar' => 'نص الزر']],
                ['key' => 'cta_url', 'type' => 'url', 'position' => 4, 'translations' => ['en' => 'CTA URL', 'ar' => 'رابط الزر']],
                ['key' => 'image', 'type' => 'image', 'position' => 5, 'translations' => ['en' => 'Image', 'ar' => 'الصورة']],
            ],
            'home.faq_contact' => [
                ['key' => 'faq_title', 'type' => 'text', 'position' => 1, 'translations' => ['en' => 'FAQ Title', 'ar' => 'عنوان الأسئلة']],
                ['key' => 'faq_items', 'type' => 'group', 'is_array' => true, 'position' => 2, 'translations' => ['en' => 'FAQ Items', 'ar' => 'عناصر الأسئلة']],
                ['key' => 'faq_question', 'type' => 'text', 'parent_key' => 'faq_items', 'position' => 1, 'translations' => ['en' => 'Question', 'ar' => 'السؤال']],
                ['key' => 'faq_answer', 'type' => 'textarea', 'parent_key' => 'faq_items', 'position' => 2, 'translations' => ['en' => 'Answer', 'ar' => 'الإجابة']],
                ['key' => 'contact_title', 'type' => 'text', 'position' => 3, 'translations' => ['en' => 'Contact Title', 'ar' => 'عنوان التواصل']],
                ['key' => 'contact_subtitle', 'type' => 'textarea', 'position' => 4, 'translations' => ['en' => 'Contact Subtitle', 'ar' => 'الوصف الفرعي للتواصل']],
            ],
            'categories.page_hero' => [
                ['key' => 'title', 'type' => 'text', 'position' => 1, 'translations' => ['en' => 'Title', 'ar' => 'العنوان']],
                ['key' => 'background_image', 'type' => 'image', 'position' => 2, 'translations' => ['en' => 'Background Image', 'ar' => 'صورة الخلفية']],
            ],
            'courses.page_hero' => [
                ['key' => 'title', 'type' => 'text', 'position' => 1, 'translations' => ['en' => 'Title', 'ar' => 'العنوان']],
                ['key' => 'illustration', 'type' => 'image', 'position' => 2, 'translations' => ['en' => 'Illustration', 'ar' => 'الرسم التوضيحي']],
            ],
            'subscription.breadcrumb_header' => [
                ['key' => 'title', 'type' => 'text', 'position' => 1, 'translations' => ['en' => 'Title', 'ar' => 'العنوان']],
            ],
            'subscription.subscription_faq' => [
                ['key' => 'title', 'type' => 'text', 'position' => 1, 'translations' => ['en' => 'Title', 'ar' => 'العنوان']],
                ['key' => 'description', 'type' => 'textarea', 'position' => 2, 'translations' => ['en' => 'Description', 'ar' => 'الوصف']],
                ['key' => 'faq_items', 'type' => 'group', 'is_array' => true, 'position' => 3, 'translations' => ['en' => 'FAQ Items', 'ar' => 'عناصر الأسئلة']],
                ['key' => 'faq_question', 'type' => 'text', 'parent_key' => 'faq_items', 'position' => 1, 'translations' => ['en' => 'Question', 'ar' => 'السؤال']],
                ['key' => 'faq_answer', 'type' => 'textarea', 'parent_key' => 'faq_items', 'position' => 2, 'translations' => ['en' => 'Answer', 'ar' => 'الإجابة']],
            ],
            'subscription.pricing_card' => [
                ['key' => 'plan_name', 'type' => 'text', 'position' => 1, 'translations' => ['en' => 'Plan Name', 'ar' => 'اسم الخطة']],
                ['key' => 'plan_subtitle', 'type' => 'textarea', 'position' => 2, 'translations' => ['en' => 'Plan Subtitle', 'ar' => 'الوصف الفرعي للخطة']],
                ['key' => 'price', 'type' => 'text', 'position' => 3, 'translations' => ['en' => 'Price', 'ar' => 'السعر']],
                ['key' => 'billing_period', 'type' => 'text', 'position' => 4, 'translations' => ['en' => 'Billing Period', 'ar' => 'فترة الدفع']],
                ['key' => 'cta_text', 'type' => 'text', 'position' => 5, 'translations' => ['en' => 'CTA Text', 'ar' => 'نص الزر']],
                ['key' => 'cta_url', 'type' => 'url', 'position' => 6, 'translations' => ['en' => 'CTA URL', 'ar' => 'رابط الزر']],
            ],
        ];
    }

    public static function initialContent(): array
    {
        return [
            'home.hero' => [
                'teacher_name' => ['en' => 'Mr Mohamed Ahmed', 'ar' => 'الأستاذ محمد أحمد'],
                'headline' => ['en' => 'Learn smarter. Grow faster. Succeed sooner.', 'ar' => 'تعلّم بذكاء. تطوّر بسرعة. وحقق النجاح أسرع.'],
                'subheadline' => ['en' => 'Build confidence and master your subjects with practical lessons and guided learning.', 'ar' => 'ابنِ ثقتك واتقن موادك مع دروس عملية وتعلم موجّه.'],
                'cta_text' => ['en' => 'Book Your Seat', 'ar' => 'احجز مقعدك'],
                'cta_url' => ['en' => '/subscription', 'ar' => '/subscription'],
                'hero_image' => ['en' => '/images/home/hero-teacher.jpg', 'ar' => '/images/home/hero-teacher.jpg'],
                'stats' => [
                    ['stats_value' => ['en' => '+40', 'ar' => '+٤٠'], 'stats_label' => ['en' => 'Courses', 'ar' => 'دورة']],
                    ['stats_value' => ['en' => '+120', 'ar' => '+١٢٠'], 'stats_label' => ['en' => 'Students', 'ar' => 'طالب']],
                    ['stats_value' => ['en' => '+80', 'ar' => '+٨٠'], 'stats_label' => ['en' => 'Lessons', 'ar' => 'درس']],
                ],
            ],
            'home.about_teacher' => [
                'avatar' => ['en' => '/images/home/teacher-avatar.jpg', 'ar' => '/images/home/teacher-avatar.jpg'],
                'title' => ['en' => 'Meet Your Instructor', 'ar' => 'تعرّف على معلمك'],
                'description' => ['en' => 'Mr Mohamed Ahmed has over 12 years of experience helping students excel through structured, practical learning.', 'ar' => 'يمتلك الأستاذ محمد أحمد أكثر من 12 عامًا من الخبرة في مساعدة الطلاب على التفوق من خلال تعلم عملي ومنهجي.'],
            ],
            'home.trust_features' => [
                'title' => ['en' => 'Why students trust us', 'ar' => 'لماذا يثق بنا الطلاب'],
                'subtitle' => ['en' => 'Everything you need to learn with clarity and consistency.', 'ar' => 'كل ما تحتاجه للتعلم بوضوح واستمرارية.'],
                'items' => [
                    ['item_title' => ['en' => 'Structured Roadmaps', 'ar' => 'مسارات تعليمية منظمة'], 'item_description' => ['en' => 'Clear weekly plans that keep you focused and on track.', 'ar' => 'خطط أسبوعية واضحة تساعدك على التركيز والالتزام.']],
                    ['item_title' => ['en' => 'Practical Lessons', 'ar' => 'دروس عملية'], 'item_description' => ['en' => 'Real examples and exercises that build lasting understanding.', 'ar' => 'أمثلة وتمارين واقعية تبني فهمًا عميقًا ومستدامًا.']],
                    ['item_title' => ['en' => 'Direct Support', 'ar' => 'دعم مباشر'], 'item_description' => ['en' => 'Get answers quickly and keep moving without confusion.', 'ar' => 'احصل على إجابات بسرعة واستمر دون تشتت أو غموض.']],
                ],
            ],
            'home.cta_banner' => [
                'title' => ['en' => 'Start your learning journey today', 'ar' => 'ابدأ رحلة تعلمك اليوم'],
                'description' => ['en' => 'Join hundreds of learners improving their skills every month.', 'ar' => 'انضم إلى مئات المتعلمين الذين يطورون مهاراتهم كل شهر.'],
                'cta_text' => ['en' => 'Book Your Seat', 'ar' => 'احجز مقعدك'],
                'cta_url' => ['en' => '/subscription', 'ar' => '/subscription'],
                'image' => ['en' => '/images/home/cta-banner.jpg', 'ar' => '/images/home/cta-banner.jpg'],
            ],
            'home.faq_contact' => [
                'faq_title' => ['en' => 'Frequently Asked Questions', 'ar' => 'الأسئلة الشائعة'],
                'faq_items' => [
                    ['faq_question' => ['en' => 'Can I start at any time?', 'ar' => 'هل يمكنني البدء في أي وقت؟'], 'faq_answer' => ['en' => 'Yes. You can enroll immediately and start learning right away.', 'ar' => 'نعم، يمكنك الاشتراك فورًا والبدء مباشرة.']],
                    ['faq_question' => ['en' => 'Are lessons suitable for beginners?', 'ar' => 'هل الدروس مناسبة للمبتدئين؟'], 'faq_answer' => ['en' => 'Absolutely. Content is designed with progressive difficulty.', 'ar' => 'بالتأكيد، تم تصميم المحتوى بتدرج مناسب للمستويات المختلفة.']],
                ],
                'contact_title' => ['en' => 'Still have questions?', 'ar' => 'ما زالت لديك أسئلة؟'],
                'contact_subtitle' => ['en' => 'Contact our support team and we will help you choose the right plan.', 'ar' => 'تواصل مع فريق الدعم وسنساعدك في اختيار الخطة المناسبة.'],
            ],
            'categories.page_hero' => [
                'title' => ['en' => 'Categories', 'ar' => 'الفئات'],
                'background_image' => ['en' => '/images/categories/hero-bg.jpg', 'ar' => '/images/categories/hero-bg.jpg'],
            ],
            'courses.page_hero' => [
                'title' => ['en' => 'Our Courses', 'ar' => 'دوراتنا'],
                'illustration' => ['en' => '/images/courses/hero-illustration.svg', 'ar' => '/images/courses/hero-illustration.svg'],
            ],
            'subscription.breadcrumb_header' => [
                'title' => ['en' => 'Ready to Start?', 'ar' => 'جاهز للانطلاق؟'],
            ],
            'subscription.subscription_faq' => [
                'title' => ['en' => 'Select your Subscription', 'ar' => 'اختر اشتراكك'],
                'description' => ['en' => 'Unlock your full potential with unlimited access to our entire library.', 'ar' => 'أطلق كامل إمكاناتك مع وصول غير محدود إلى مكتبتنا كاملة.'],
                'faq_items' => [
                    ['faq_question' => ['en' => 'What is included in the plan?', 'ar' => 'ماذا تتضمن الخطة؟'], 'faq_answer' => ['en' => 'Full access to all current courses and upcoming lessons.', 'ar' => 'وصول كامل إلى جميع الدورات الحالية والدروس القادمة.']],
                    ['faq_question' => ['en' => 'Can I cancel anytime?', 'ar' => 'هل يمكنني الإلغاء في أي وقت؟'], 'faq_answer' => ['en' => 'Yes, you can manage and cancel your subscription from your account settings.', 'ar' => 'نعم، يمكنك إدارة اشتراكك وإلغاؤه من إعدادات حسابك.']],
                ],
            ],
            'subscription.pricing_card' => [
                'plan_name' => ['en' => 'EduPlatform All-Access', 'ar' => 'الوصول الكامل لمنصة EduPlatform'],
                'plan_subtitle' => ['en' => 'Best value for dedicated learners', 'ar' => 'أفضل قيمة للمتعلمين الجادين'],
                'price' => ['en' => '600', 'ar' => '٦٠٠'],
                'billing_period' => ['en' => '/ Month', 'ar' => '/ شهر'],
                'cta_text' => ['en' => 'Continue', 'ar' => 'متابعة'],
                'cta_url' => ['en' => '/checkout', 'ar' => '/checkout'],
            ],
        ];
    }
}
