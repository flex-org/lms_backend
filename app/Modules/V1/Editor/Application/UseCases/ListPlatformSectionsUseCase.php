<?php

namespace App\Modules\V1\Editor\Application\UseCases;

use App\Modules\V1\Editor\Domain\Repositories\PlatformSectionRepositoryInterface;
use Illuminate\Support\Collection;

final readonly class ListPlatformSectionsUseCase
{
    public function __construct(
        private PlatformSectionRepositoryInterface $repository,
    ) {
    }

    public function execute(int $platformPageId): Collection
    {
        return $this->repository->listByPlatformPage($platformPageId);
    }
}
