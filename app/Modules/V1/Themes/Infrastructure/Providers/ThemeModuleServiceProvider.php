<?php

namespace App\Modules\V1\Themes\Infrastructure\Providers;

use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\V1\Themes\Domain\Repositories\ThemeRepositoryInterface;
use App\Modules\V1\Themes\Infrastructure\Persistence\EloquentThemeRepository;

class ThemeModuleServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(
            ThemeRepositoryInterface::class,
            EloquentThemeRepository::class
        );
    }
}
