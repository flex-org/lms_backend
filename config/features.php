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
        'builder' => 'feature-17',
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Capabilities
    |--------------------------------------------------------------------------
    |
    | All capabilities given to the owner role. Capabilities NOT listed in
    | owner_only_capabilities are also given to the admin role.
    |
    */
    'admin_capabilities' => [
        'manage-admins',
        'manage-roles',
        'manage-platform-settings',
        'manage-courses',
        'manage-students',
        'view-reports',
    ],

    /*
    |--------------------------------------------------------------------------
    | Owner-Only Capabilities
    |--------------------------------------------------------------------------
    |
    | Capabilities restricted to the owner role. The admin role will NOT
    | receive these. Must be a subset of admin_capabilities.
    |
    */
    'owner_only_capabilities' => [
        'manage-admins',
        'manage-roles',
        'manage-platform-settings',
    ],
];
