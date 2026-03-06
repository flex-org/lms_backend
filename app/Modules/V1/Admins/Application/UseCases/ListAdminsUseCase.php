<?php

namespace App\Modules\V1\Admins\Application\UseCases;

use App\Modules\V1\Admins\Domain\Repositories\AdminRepositoryInterface;
use Illuminate\Support\Collection;

final readonly class ListAdminsUseCase
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
    ) {
    }

    public function execute(int $platformId): Collection
    {
        return $this->adminRepository->listByPlatform($platformId);
    }
}

