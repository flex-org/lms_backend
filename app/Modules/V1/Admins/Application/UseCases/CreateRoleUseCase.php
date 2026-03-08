<?php

namespace App\Modules\V1\Admins\Application\UseCases;

use Spatie\Permission\Models\Role;

final readonly class CreateRoleUseCase
{
    public function execute(string $name, array $permissions): Role
    {
        $role = Role::create([
            'name' => $name,
            'guard_name' => 'admins',
        ]);

        $role->syncPermissions(
            collect($permissions)->filter(fn (string $p) => str_starts_with($p, 'admin:'))->all()
        );

        return $role->load('permissions:id,name');
    }
}
