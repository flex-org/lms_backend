<?php

namespace App\Modules\V1\Editor\Application\UseCases;

use App\Modules\V1\Editor\Application\DTOs\CreatePlatformPageData;
use App\Modules\V1\Editor\Domain\Models\PlatformPage;
use App\Modules\V1\Editor\Domain\Repositories\PlatformPageRepositoryInterface;

final readonly class CreatePlatformPageUseCase
{
    public function __construct(
        private PlatformPageRepositoryInterface $repository,
    ) {
    }

    public function execute(CreatePlatformPageData $data): PlatformPage
    {
        return $this->repository->create($data->toAttributes());
    }
}
