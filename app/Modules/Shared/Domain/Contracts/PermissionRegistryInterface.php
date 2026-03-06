<?php

namespace App\Modules\Shared\Domain\Contracts;

interface PermissionRegistryInterface
{
    public function featurePermission(int|string $featureId): string;

    public function resolveFeaturePermission(string $featureKey): string;

    public function adminCapability(string $capability): string;

    public function guards(): array;
}
