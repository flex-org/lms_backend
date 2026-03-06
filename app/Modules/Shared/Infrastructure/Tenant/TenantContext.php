<?php

namespace App\Modules\Shared\Infrastructure\Tenant;

use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Platforms\Domain\Models\Platform;

class TenantContext implements TenantContextInterface
{
    private ?string $domain = null;
    private ?Platform $platform = null;

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
        $this->platform = Platform::withoutGlobalScopes()
            ->where('domain', $domain)
            ->firstOrFail();

        config(['platform.domain' => $domain]);
    }

    public function getPlatform(): ?Platform
    {
        return $this->platform;
    }

    public function getPlatformId(): ?int
    {
        return $this->platform?->id;
    }

    public function getPlatformByDomain(string $domain): ?Platform
    {
        return Platform::withoutGlobalScopes()
            ->where('domain', $domain)
            ->first();
    }

    public function isResolved(): bool
    {
        return $this->domain !== null && $this->platform !== null;
    }
}
