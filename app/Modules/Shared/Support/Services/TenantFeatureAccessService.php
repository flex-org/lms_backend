<?php

namespace App\Modules\Shared\Support\Services;

use App\Modules\Shared\Domain\Contracts\PermissionRegistryInterface;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class TenantFeatureAccessService
{
    public function __construct(
        private readonly PermissionRegistryInterface $permissionRegistry,
    ) {
    }

    public function hasAccess(Platform $platform, string $feature): bool
    {
        $permission = $this->permissionRegistry->featurePermission($feature);
        try {
            return $platform->hasPermissionTo($permission);
        } catch (PermissionDoesNotExist) {
            return false;
        }
    }

}
