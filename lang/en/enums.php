<?php

return [
    'selling_system' => [
        'ca' => [
            'label' => 'Categories',
            'description' => 'Sell access to categories that group multiple courses.',
        ],
        'co' => [
            'label' => 'Courses',
            'description' => 'Sell individual courses directly to students.',
        ],
        's' => [
            'label' => 'Sessions',
            'description' => 'Sell access to live or scheduled sessions.',
        ],
        'ss' => [
            'label' => 'Subscriptions',
            'description' => 'Sell recurring subscriptions that unlock multiple items.',
        ],
    ],
    'plan_type' => [
        'basic' => 'Basic',
        'pro' => 'Pro',
    ],
    'subscription_status' => [
        'active' => 'Active',
        'expired' => 'Expired',
        'free_trial' => 'Free Trial',
        'pending' => 'Pending',
        'deactivated' => 'Deactivated',
    ],
    'invoice_item_type' => [
        'feature' => 'Feature',
        'storage' => 'Storage',
        'capacity' => 'Capacity',
        'mobile_app' => 'Mobile App',
    ],
    'invoice_status' => [
        'pending' => 'Pending',
        'paid' => 'Paid',
        'overdue' => 'Overdue',
        'cancelled' => 'Cancelled',
    ],
    'invoice_type' => [
        'monthly' => 'Monthly',
        'proration' => 'Proration',
    ],
    'pending_change_type' => [
        'add_feature' => 'Add Feature',
        'increase_storage' => 'Increase Storage',
        'increase_capacity' => 'Increase Capacity',
        'enable_mobile' => 'Enable Mobile',
    ],
    'pending_change_status' => [
        'pending' => 'Pending',
        'applied' => 'Applied',
        'cancelled' => 'Cancelled',
    ],
    'dynamic_features' => [
        'storage' => 'Storage',
        'capacity' => 'Capacity',
        'mobile_app' => 'Mobile App',
    ],
];
