<?php

namespace App\Modules\V1\Admins\Application\UseCases;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;

final readonly class ListPermissionsUseCase
{
    public function execute(): Collection
    {
        return Permission::where('guard_name', 'admins')
            ->where('name', 'like', 'admin:%')
            ->get(['id', 'name']);
    }
}
