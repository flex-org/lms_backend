<?php

namespace App\Modules\V1\Admins\Presentation\Http\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Admins\Application\DTOs\CreateAdminData;
use App\Modules\V1\Admins\Application\DTOs\UpdateAdminData;
use App\Modules\V1\Admins\Application\UseCases\CreateAdminUseCase;
use App\Modules\V1\Admins\Application\UseCases\DeleteAdminUseCase;
use App\Modules\V1\Admins\Application\UseCases\ListAdminsUseCase;
use App\Modules\V1\Admins\Application\UseCases\UpdateAdminUseCase;
use App\Modules\V1\Admins\Domain\Models\Admin;
use App\Modules\V1\Admins\Presentation\Http\Requests\StoreAdminRequest;
use App\Modules\V1\Admins\Presentation\Http\Requests\UpdateAdminRequest;

class AdminManagementController extends Controller
{
    public function __construct(
        private readonly ListAdminsUseCase $listAdmins,
        private readonly CreateAdminUseCase $createAdmin,
        private readonly UpdateAdminUseCase $updateAdmin,
        private readonly DeleteAdminUseCase $deleteAdmin,
        private readonly TenantContextInterface $tenantContext,
    ) {
    }

    public function index()
    {
        $this->authorize('viewAny', Admin::class);

        $admins = $this->listAdmins->execute($this->tenantContext->getPlatformId());

        return ApiResponse::success($admins);
    }

    public function store(StoreAdminRequest $request)
    {
        $this->authorize('create', Admin::class);

        $data = CreateAdminData::fromArray(
            $request->validated(),
            $this->tenantContext->getPlatformId(),
        );

        $admin = $this->createAdmin->execute($data);

        return ApiResponse::created(['admin' => $admin]);
    }

    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $this->authorize('update', $admin);

        $data = UpdateAdminData::fromArray($request->validated());

        $updated = $this->updateAdmin->execute($admin, $data);

        return ApiResponse::updated(['admin' => $updated]);
    }

    public function destroy(Admin $admin)
    {
        $this->authorize('delete', $admin);

        $this->deleteAdmin->execute($admin);

        return ApiResponse::deleted();
    }
}
