<?php

namespace App\Modules\V1\Admins\Application\UseCases;

use App\Modules\V1\Admins\Application\DTOs\CreateAdminData;
use App\Modules\V1\Admins\Domain\Models\Admin;
use App\Modules\V1\Admins\Domain\Repositories\AdminRepositoryInterface;

final readonly class CreateAdminUseCase
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
    ) {
    }

    public function execute(CreateAdminData $data): Admin
    {
        return $this->adminRepository->create(
            $data->toAttributes() + ['role' => $data->role],
        );
    }
}

