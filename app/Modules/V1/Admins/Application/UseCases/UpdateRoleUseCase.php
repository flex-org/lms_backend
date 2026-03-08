<?php

namespace App\Modules\V1\Admins\Application\UseCases;

use Spatie\Permission\Models\Role;

final readonly class UpdateRoleUseCase
{
    public function execute(Role $role, ?string $name, ?array $permissions): Role
    {
        if ($name !== null) {
            $role->update(['name' => $name]);
        }

        if ($permissions !== null) {
            $role->syncPermissions(
                collect($permissions)->filter(fn (string $p) => str_starts_with($p, 'admin:'))->all()
            );
        }

        return $role->load('permissions:id,name');
    }
}
