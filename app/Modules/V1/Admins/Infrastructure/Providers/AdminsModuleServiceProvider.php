<?php

namespace App\Modules\V1\Admins\Infrastructure\Providers;

use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\V1\Admins\Domain\Models\Admin;
use App\Modules\V1\Admins\Domain\Policies\AdminPolicy;
use App\Modules\V1\Admins\Domain\Policies\RolePolicy;
use App\Modules\V1\Admins\Domain\Repositories\AdminRepositoryInterface;
use App\Modules\V1\Admins\Infrastructure\Persistence\Repositories\EloquentAdminRepository;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class AdminsModuleServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(AdminRepositoryInterface::class, EloquentAdminRepository::class);
    }

    public function boot(): void
    {
        parent::boot();

        Gate::policy(Admin::class, AdminPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
    }
}
