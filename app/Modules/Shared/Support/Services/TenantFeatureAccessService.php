<?php

namespace App\Modules\Shared\Support\Services;

use App\Modules\Shared\Domain\Contracts\PermissionRegistryInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class TenantFeatureAccessService
{
    public function __construct(
        private readonly PermissionRegistryInterface $permissionRegistry,
    ) {
    }

    public function hasAccess(Authenticatable $user, string $feature): bool
    {
        if (! method_exists($user, 'hasPermissionTo')) {
            return false;
        }

        $permission = $this->permissionRegistry->resolveFeaturePermission($feature);
        $guard = $this->resolveGuard($user);

        try {
            return $user->hasPermissionTo($permission, $guard);
        } catch (PermissionDoesNotExist) {
            return false;
        }
    }

    private function resolveGuard(Authenticatable $user): ?string
    {
        return property_exists($user, 'guard_name') ? $user->guard_name : null;
    }
}
