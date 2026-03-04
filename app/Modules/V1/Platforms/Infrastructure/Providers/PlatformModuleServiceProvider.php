<?php

namespace App\Modules\V1\Platforms\Infrastructure\Providers;

use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\V1\Platforms\Domain\Repositories\AdminRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Repositories\FeatureRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Repositories\PlatformRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Repositories\ThemeRepositoryInterface;
use App\Modules\V1\Platforms\Infrastructure\Persistence\Repositories\EloquentAdminRepository;
use App\Modules\V1\Platforms\Infrastructure\Persistence\Repositories\EloquentFeatureRepository;
use App\Modules\V1\Platforms\Infrastructure\Persistence\Repositories\EloquentPlatformRepository;
use App\Modules\V1\Platforms\Infrastructure\Persistence\Repositories\EloquentThemeRepository;

class PlatformModuleServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(PlatformRepositoryInterface::class, EloquentPlatformRepository::class);
        $this->app->bind(FeatureRepositoryInterface::class, EloquentFeatureRepository::class);
        $this->app->bind(ThemeRepositoryInterface::class, EloquentThemeRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, EloquentAdminRepository::class);
    }
}
