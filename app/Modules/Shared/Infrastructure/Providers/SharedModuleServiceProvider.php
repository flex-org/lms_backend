<?php

namespace App\Modules\Shared\Infrastructure\Providers;

use App\Modules\Shared\Domain\Contracts\PermissionRegistryInterface;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\Shared\Infrastructure\Permissions\PermissionRegistry;
use App\Modules\Shared\Infrastructure\Tenant\TenantContext;
use Illuminate\Support\ServiceProvider;

class SharedModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TenantContextInterface::class, TenantContext::class);
        $this->app->singleton(PermissionRegistryInterface::class, PermissionRegistry::class);
    }
}
