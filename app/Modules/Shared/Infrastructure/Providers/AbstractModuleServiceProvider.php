<?php

namespace App\Modules\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

abstract class AbstractModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerBindings();
    }

    public function boot(): void
    {
        $this->bootRoutes();
        $this->bootResources();
        $this->bootMigrations();
    }

    protected function registerBindings(): void
    {
    }

    protected function bootRoutes(): void
    {
    }

    protected function bootResources(): void
    {
    }

    protected function bootMigrations(): void
    {
    }
}
