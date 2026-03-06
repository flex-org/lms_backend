<?php

namespace App\Modules\Shared\Domain\Contracts;

use App\Modules\V1\Platforms\Domain\Models\Platform;

interface TenantContextInterface
{
    public function getDomain(): ?string;

    public function setDomain(string $domain): void;

    public function getPlatform(): ?Platform;

    public function getPlatformId(): ?int;

    public function getPlatformByDomain(string $domain): ?Platform;

    public function isResolved(): bool;
}
