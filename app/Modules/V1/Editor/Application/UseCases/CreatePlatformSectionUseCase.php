<?php

namespace App\Modules\V1\Editor\Application\UseCases;

use App\Modules\V1\Editor\Application\DTOs\CreatePlatformSectionData;
use App\Modules\V1\Editor\Domain\Models\PlatformSection;
use App\Modules\V1\Editor\Domain\Repositories\PlatformSectionRepositoryInterface;

final readonly class CreatePlatformSectionUseCase
{
    public function __construct(
        private PlatformSectionRepositoryInterface $repository,
    ) {
    }

    public function execute(CreatePlatformSectionData $data): PlatformSection
    {
        return $this->repository->create($data->toAttributes());
    }
}
