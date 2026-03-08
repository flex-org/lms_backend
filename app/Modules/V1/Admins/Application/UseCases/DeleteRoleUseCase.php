<?php

namespace App\Modules\V1\Admins\Application\UseCases;

use Spatie\Permission\Models\Role;

final readonly class DeleteRoleUseCase
{
    public function execute(Role $role): void
    {
        $role->delete();
    }
}
