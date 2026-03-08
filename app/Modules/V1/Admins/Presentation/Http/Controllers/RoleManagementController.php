<?php

namespace App\Modules\V1\Admins\Presentation\Http\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\Admins\Application\UseCases\CreateRoleUseCase;
use App\Modules\V1\Admins\Application\UseCases\DeleteRoleUseCase;
use App\Modules\V1\Admins\Application\UseCases\ListPermissionsUseCase;
use App\Modules\V1\Admins\Application\UseCases\ListRolesUseCase;
use App\Modules\V1\Admins\Application\UseCases\UpdateRoleUseCase;
use App\Modules\V1\Admins\Presentation\Http\Requests\StoreRoleRequest;
use App\Modules\V1\Admins\Presentation\Http\Requests\UpdateRoleRequest;
use Spatie\Permission\Models\Role;

class RoleManagementController extends Controller
{
    public function __construct(
        private readonly ListRolesUseCase $listRoles,
        private readonly CreateRoleUseCase $createRole,
        private readonly UpdateRoleUseCase $updateRole,
        private readonly DeleteRoleUseCase $deleteRole,
        private readonly ListPermissionsUseCase $listPermissions,
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Role::class);

        return ApiResponse::success($this->listRoles->execute());
    }

    public function store(StoreRoleRequest $request)
    {
        $this->authorize('create', Role::class);

        $role = $this->createRole->execute(
            $request->validated('name'),
            $request->validated('permissions'),
        );

        return ApiResponse::created(['role' => $role]);
    }

    public function show(Role $role)
    {
        $this->authorize('view', $role);

        $role->load('permissions:id,name');

        return ApiResponse::success($role);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->authorize('update', $role);

        $validated = $request->validated();

        $role = $this->updateRole->execute(
            $role,
            $validated['name'] ?? null,
            $validated['permissions'] ?? null,
        );

        return ApiResponse::updated(['role' => $role]);
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        $this->deleteRole->execute($role);

        return ApiResponse::deleted();
    }

    public function permissions()
    {
        $this->authorize('viewAny', Role::class);

        return ApiResponse::success($this->listPermissions->execute());
    }
}
