<?php

namespace App\Modules\V1\Platforms\Application\UseCases;

use App\Modules\V1\Platforms\Application\UseCases\CreatePlatform\CreatePlatformAction;
use App\Modules\V1\Platforms\Application\DTOs\CreatePlatformData;
use App\Modules\V1\Platforms\Domain\Repositories\PlatformRepositoryInterface;

class PlatformService
{
    public function __construct(
        private readonly CreatePlatformAction $createPlatformAction,
        private readonly PlatformRepositoryInterface $platformRepository,
    ) {
    }

    public function create(array $platformData): array
    {
        return $this->createPlatformAction->execute(CreatePlatformData::fromArray($platformData));
    }

    public function domainExists(?string $domain): bool
    {
        if (! $domain) {
            return false;
        }

        return $this->platformRepository->domainExists($domain);
    }
}
