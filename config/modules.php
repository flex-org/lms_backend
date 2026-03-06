<?php

return [
    'providers' => [
        App\Modules\Shared\Infrastructure\Providers\SharedModuleServiceProvider::class,
        App\Modules\V1\Platforms\Infrastructure\Providers\PlatformModuleServiceProvider::class,
        App\Modules\V1\Admins\Infrastructure\Providers\AdminsModuleServiceProvider::class,
        App\Modules\V1\Editor\Infrastructure\Providers\EditorModuleServiceProvider::class,
    ],
];
