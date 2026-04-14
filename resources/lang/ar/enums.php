<?php

return [
    'selling_system' => [
        'ca' => [
            'label' => 'الفئات',
            'description' => 'بيع الوصول إلى فئات تضم عدة دورات.',
        ],
        'co' => [
            'label' => 'الدورات',
            'description' => 'بيع الدورات بشكل فردي مباشرة للطلاب.',
        ],
        's' => [
            'label' => 'الجلسات',
            'description' => 'بيع الوصول إلى الجلسات المباشرة أو المجدولة.',
        ],
        'ss' => [
            'label' => 'الاشتراكات',
            'description' => 'بيع اشتراكات دورية تفتح الوصول لعدة عناصر.',
        ],
    ],
    'plan_type' => [
        'basic' => 'أساسي',
        'pro' => 'احترافي',
    ],
    'subscription_status' => [
        'active' => 'نشط',
        'expired' => 'منتهي',
        'free_trial' => 'تجربة مجانية',
        'pending' => 'قيد الانتظار',
        'deactivated' => 'معطل',
    ],
    'invoice_item_type' => [
        'feature' => 'ميزة',
        'storage' => 'تخزين',
        'capacity' => 'سعة',
        'mobile_app' => 'تطبيق جوال',
    ],
    'invoice_status' => [
        'pending' => 'قيد الانتظار',
        'paid' => 'مدفوعة',
        'overdue' => 'متأخرة',
        'cancelled' => 'ملغاة',
    ],
    'invoice_type' => [
        'monthly' => 'شهري',
        'proration' => 'احتساب نسبي',
    ],
    'pending_change_type' => [
        'add_feature' => 'إضافة ميزة',
        'increase_storage' => 'زيادة التخزين',
        'increase_capacity' => 'زيادة السعة',
        'enable_mobile' => 'تفعيل الجوال',
    ],
    'pending_change_status' => [
        'pending' => 'قيد الانتظار',
        'applied' => 'تم التطبيق',
        'cancelled' => 'ملغى',
    ],
    'dynamic_features' => [
        'storage' => 'التخزين',
        'capacity' => 'السعة',
        'mobile_app' => 'تطبيق الجوال',
    ],
];
