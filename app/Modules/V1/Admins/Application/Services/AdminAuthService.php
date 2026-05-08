<?php

namespace App\Modules\V1\Admins\Application\Services;

use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Admins\Domain\Models\Admin;
use App\Modules\V1\Platforms\Application\UseCases\GetPlatformOverViewUseCase;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AdminAuthService
{
    public function __construct(
        private readonly TenantContextInterface $tenantContext,
    ) {
    }

    public function login(array $credentials, GetPlatformOverViewUseCase $getPlatformOverViewUseCase) : array
    {
        if (! $user = $this->checkUser($credentials))
            throw new UnauthorizedHttpException('', __('auth.credentials_mismatch'));

        $abilities = ['dashboard', $this->tenantContext->getDomain()];

        $platformOverView = $getPlatformOverViewUseCase->execute(
            $this->tenantContext->getPlatform()
        );

        return array_merge($platformOverView,[
            'access_token' => $user->createToken('dashboard', $abilities)->plainTextToken,
            'user' => $user,
        ]);
    }

    public function checkUser($credentials): ?Admin
    {
        $user = Admin::whereEmail($credentials['email'])->first();

        return ($user && Hash::check($credentials['password'], $user->password)) ?
            $user : null;
    }
}
