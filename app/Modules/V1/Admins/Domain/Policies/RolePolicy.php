<?php

namespace App\Modules\V1\Admins\Domain\Policies;

use App\Modules\V1\Admins\Domain\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $admin): bool
    {
        return $admin->hasPermissionTo('admin:manage-roles', 'admins');
    }

    public function view(Admin $admin, Role $role): bool
    {
        return $admin->hasPermissionTo('admin:manage-roles', 'admins');
    }

    public function create(Admin $admin): bool
    {
        return $admin->hasPermissionTo('admin:manage-roles', 'admins');
    }

    public function update(Admin $admin, Role $role): bool
    {
        return $admin->hasPermissionTo('admin:manage-roles', 'admins')
            && ! in_array($role->name, ['owner']);
    }

    public function delete(Admin $admin, Role $role): bool
    {
        return $admin->hasPermissionTo('admin:manage-roles', 'admins')
            && ! in_array($role->name, ['owner', 'admin']);
    }
}
