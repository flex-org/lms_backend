<?php

return [
    'providers' => [
        App\Modules\Shared\Infrastructure\Providers\SharedModuleServiceProvider::class,
        App\Modules\V1\Platforms\Infrastructure\Providers\PlatformModuleServiceProvider::class,
        App\Modules\V1\Admins\Infrastructure\Providers\AdminsModuleServiceProvider::class,
        App\Modules\V1\Editor\Infrastructure\Providers\EditorModuleServiceProvider::class,
        App\Modules\V1\Categories\Infrastructure\Providers\CategoryModuleServiceProvider::class,
        App\Modules\V1\Courses\Infrastructure\Providers\CourseModuleServiceProvider::class,
        App\Modules\V1\Billing\Infrastructure\Providers\BillingModuleServiceProvider::class,
        App\Modules\V1\Features\Infrastructure\Providers\FeatureModuleServiceProvider::class,
        App\Modules\V1\Themes\Infrastructure\Providers\ThemeModuleServiceProvider::class,

    ],
];
