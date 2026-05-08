<?php

namespace App\Modules\V1\Platforms\Application\UseCases;

use App\Modules\V1\Platforms\Domain\Models\Platform;
use App\Modules\V1\Platforms\Domain\Repositories\PlatformRepositoryInterface;

class GetPlatformOverViewUseCase
{
    public function __construct(
        private readonly PlatformRepositoryInterface $platformRepository,
    ) {
    }

    public function execute(Platform $platform): array
    {
        return [
            'features' => $this->platformRepository->getPlatformFeatures($platform),
            'selling_systems' => $this->platformRepository->getPlatformSellingSystems($platform),
            'theme' => $this->platformRepository->getPlatformTheme($platform),
            'template' => [
                'id' => 1,
                'name' => 'Default',
            ]
        ];
    }

}
