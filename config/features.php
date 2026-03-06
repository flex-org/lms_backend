<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feature Permission Mapping
    |--------------------------------------------------------------------------
    |
    | Maps business feature keys (used in middleware and code) to the actual
    | permission names stored in the database. This allows middleware like
    | `featureAccess:builder` to resolve to the correct permission string.
    |
    */
    'permissions' => [
        'courses' => 'feature-1',
        'students' => 'feature-2',
        'content' => 'feature-3',
        'roles' => 'feature-4',
        'security' => 'feature-5',
        'reports' => 'feature-6',
        'settings' => 'feature-7',
        'payments' => 'feature-8',
        'categories' => 'feature-9',
        'assignments' => 'feature-10',
        'question-bank' => 'feature-11',
        'quizzes' => 'feature-12',
        'announcements' => 'feature-13',
        'live-sessions' => 'feature-14',
        'certificates' => 'feature-15',
        'calendar' => 'feature-16',
        'builder' => 'feature-builder',
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Capabilities
    |--------------------------------------------------------------------------
    |
    | Standard admin capability names. These are available for fine-grained
    | admin permission checks beyond the owner/admin role distinction.
    |
    */
    'admin_capabilities' => [
        'manage-admins',
        'manage-platform-settings',
        'manage-courses',
        'manage-students',
        'view-reports',
    ],
];
