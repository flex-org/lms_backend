<?php

namespace App\Modules\V1\Platforms\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Dashboard\Admins\Domain\Models\Admin;
use App\Modules\V1\Platforms\Domain\Repositories\AdminRepositoryInterface;

class EloquentAdminRepository implements AdminRepositoryInterface
{
    public function createOwner(array $attributes): Admin
    {
        $admin = Admin::create($attributes);
        $admin->assignRole('owner');

        return $admin;
    }
}
