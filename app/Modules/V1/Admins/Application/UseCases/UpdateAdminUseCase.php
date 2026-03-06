<?php

namespace App\Modules\V1\Admins\Application\UseCases;

use App\Modules\V1\Admins\Application\DTOs\UpdateAdminData;
use App\Modules\V1\Admins\Domain\Models\Admin;
use App\Modules\V1\Admins\Domain\Repositories\AdminRepositoryInterface;

final readonly class UpdateAdminUseCase
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
    ) {
    }

    public function execute(Admin $admin, UpdateAdminData $data): Admin
    {
        $attributes = $data->toAttributes();

        if ($data->role !== null) {
            $attributes['role'] = $data->role;
        }

        return $this->adminRepository->update($admin, $attributes);
    }
}

