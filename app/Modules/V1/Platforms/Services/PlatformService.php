<?php

namespace App\Modules\V1\Platforms\Services;

use App\Modules\V1\Platforms\Application\CreatePlatform\CreatePlatformAction;
use App\Modules\V1\Platforms\Application\CreatePlatform\CreatePlatformData;
use App\Modules\V1\Platforms\Models\Platform;

class PlatformService
{
    public function __construct(private readonly CreatePlatformAction $createPlatformAction)
    {
    }

    public function create(array $platformData): array
    {
        return $this->createPlatformAction->execute(CreatePlatformData::fromArray($platformData));
    }

    public static function domainExists(?string $domain): bool
    {
        if (! $domain) {
            return false;
        }

        return Platform::where('domain', $domain)->exists();
    }
}
