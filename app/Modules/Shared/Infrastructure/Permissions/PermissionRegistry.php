<?php

namespace App\Modules\Shared\Infrastructure\Permissions;

use App\Modules\Shared\Domain\Contracts\PermissionRegistryInterface;

class PermissionRegistry implements PermissionRegistryInterface
{
    private const FEATURE_PREFIX = 'feature-';
    private const ADMIN_PREFIX = 'admin:';

    public function featurePermission(int|string $featureId): string
    {
        return self::FEATURE_PREFIX . $featureId;
    }

    public function resolveFeaturePermission(string $featureKey): string
    {
        if (is_numeric($featureKey)) {
            return $this->featurePermission($featureKey);
        }

        $mapped = config('features.permissions.' . $featureKey);

        return is_string($mapped) && $mapped !== '' ? $mapped : $featureKey;
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
