<?php

namespace App\Modules\V1\Admins\Domain\Repositories;

use App\Modules\V1\Admins\Domain\Models\Admin;
use Illuminate\Support\Collection;

interface AdminRepositoryInterface
{
    public function listByPlatform(int $platformId): Collection;

    public function create(array $attributes): Admin;

    public function update(Admin $admin, array $attributes): Admin;

    public function delete(Admin $admin): void;
}

