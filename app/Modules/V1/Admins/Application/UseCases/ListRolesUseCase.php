<?php

namespace App\Modules\V1\Admins\Application\UseCases;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

final readonly class ListRolesUseCase
{
    public function execute(): Collection
    {
        return Role::where('guard_name', 'admins')
            ->with('permissions:id,name')
            ->get(['id', 'name', 'guard_name', 'created_at']);
    }
}
