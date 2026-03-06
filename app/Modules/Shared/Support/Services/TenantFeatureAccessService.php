<?php

namespace App\Modules\Shared\Support\Services;

use Illuminate\Contracts\Auth\Authenticatable;

class TenantFeatureAccessService
{
    public function hasAccess(Authenticatable $user, string $feature): bool
    {
        if (! method_exists($user, 'hasPermissionTo')) {
            return false;
        }

        $permission = $this->resolvePermission($feature);

        return $user->hasPermissionTo($permission, $this->resolveGuard($user));
    }

    private function resolvePermission(string $feature): string
    {
        if (is_numeric($feature)) {
            return 'feature-' . $feature;
        }

        $mapped = config('features.permissions.' . $feature);

        return is_string($mapped) && $mapped !== '' ? $mapped : $feature;
    }

    private function resolveGuard(Authenticatable $user): ?string
    {
        return property_exists($user, 'guard_name') ? $user->guard_name : null;
    }
}
