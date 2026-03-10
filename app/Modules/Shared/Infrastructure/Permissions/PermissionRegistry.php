<?php

namespace App\Modules\Shared\Infrastructure\Permissions;

use App\Modules\Shared\Domain\Contracts\PermissionRegistryInterface;

class PermissionRegistry implements PermissionRegistryInterface
{
    private const FEATURE_PREFIX = 'feature-';
    private const ADMIN_PREFIX = 'admin:';

    public function featurePermission(int|string $featureKey): string
    {
        return self::FEATURE_PREFIX . $featureKey;
    }

    public function resolveFeaturePermission(string $featureKey): string
    {
        return $this->featurePermission($featureKey);
    }

    public function adminCapability(string $capability): string
    {
        return self::ADMIN_PREFIX . $capability;
    }

    public function guards(): array
    {
        return ['sanctum', 'admins'];
    }
}
