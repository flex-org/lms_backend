<?php

namespace App\Modules\V1\Editor\Infrastructure\Providers;

use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\V1\Editor\Domain\Repositories\PlatformPageRepositoryInterface;
use App\Modules\V1\Editor\Domain\Repositories\PlatformSectionRepositoryInterface;
use App\Modules\V1\Editor\Infrastructure\Persistence\Repositories\EloquentPlatformPageRepository;
use App\Modules\V1\Editor\Infrastructure\Persistence\Repositories\EloquentPlatformSectionRepository;

class EditorModuleServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(PlatformPageRepositoryInterface::class, EloquentPlatformPageRepository::class);
        $this->app->bind(PlatformSectionRepositoryInterface::class, EloquentPlatformSectionRepository::class);
    }
}
