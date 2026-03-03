<?php

namespace App\Modules\V1\Platforms\Domain\Repositories;

use App\Modules\V1\Dashboard\Admins\Models\Admin;

interface AdminRepositoryInterface
{
    public function createOwner(array $attributes): Admin;
}
