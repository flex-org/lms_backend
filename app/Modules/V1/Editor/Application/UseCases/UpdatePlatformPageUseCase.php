<?php

namespace App\Modules\V1\Editor\Application\UseCases;

use App\Modules\V1\Editor\Domain\Models\PlatformPage;
use App\Modules\V1\Editor\Domain\Repositories\PlatformPageRepositoryInterface;

final readonly class UpdatePlatformPageUseCase
{
    public function __construct(
        private PlatformPageRepositoryInterface $repository,
    ) {
    }

    public function execute(PlatformPage $page, array $attributes): PlatformPage
    {
        return $this->repository->update($page, $attributes);
    }
}
