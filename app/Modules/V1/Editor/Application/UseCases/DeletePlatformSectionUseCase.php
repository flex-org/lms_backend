<?php

namespace App\Modules\V1\Editor\Application\UseCases;

use App\Modules\V1\Editor\Domain\Models\PlatformSection;
use App\Modules\V1\Editor\Domain\Repositories\PlatformSectionRepositoryInterface;

final readonly class DeletePlatformSectionUseCase
{
    public function __construct(
        private PlatformSectionRepositoryInterface $repository,
    ) {
    }

    public function execute(PlatformSection $section): void
    {
        $this->repository->delete($section);
    }
}
