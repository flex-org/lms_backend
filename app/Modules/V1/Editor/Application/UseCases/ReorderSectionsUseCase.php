<?php

namespace App\Modules\V1\Editor\Application\UseCases;

use App\Modules\V1\Editor\Domain\Repositories\PlatformSectionRepositoryInterface;

final readonly class ReorderSectionsUseCase
{
    public function __construct(
        private PlatformSectionRepositoryInterface $repository,
    ) {
    }

    public function execute(int $platformPageId, array $orderedIds): void
    {
        $this->repository->reorder($platformPageId, $orderedIds);
    }
}
