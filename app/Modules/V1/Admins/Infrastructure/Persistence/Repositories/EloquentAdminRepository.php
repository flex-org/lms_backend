<?php

namespace App\Modules\V1\Admins\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Admins\Domain\Models\Admin;
use App\Modules\V1\Admins\Domain\Repositories\AdminRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentAdminRepository implements AdminRepositoryInterface
{
    public function listByPlatform(int $platformId): Collection
    {
        return Admin::where('platform_id', $platformId)->get();
    }

    public function create(array $attributes): Admin
    {
        $admin = Admin::create($attributes);

        if (isset($attributes['role'])) {
            $admin->assignRole($attributes['role']);
        }

        return $admin;
    }

    public function update(Admin $admin, array $attributes): Admin
    {
        $admin->fill($attributes);

        if (array_key_exists('role', $attributes)) {
            $admin->syncRoles([$attributes['role']]);
        }

        $admin->save();

        return $admin;
    }

    public function delete(Admin $admin): void
    {
        $admin->delete();
    }
}

