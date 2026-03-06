<?php

namespace App\Modules\V1\Admins\Application\Services;

use App\Facades\ApiResponse;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Admins\Domain\Models\Admin;
use App\Modules\V1\Utilities\Support\Services\AuthServices;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AdminAuthServices extends AuthServices
{
    public function __construct(
        private readonly TenantContextInterface $tenantContext,
    ) {
    }

    public function login($credentials)
    {
        if (! $user = $this->checkUser($credentials)) {
            return ApiResponse::message(
                'Your credentials doesn\'t match our records',
                Response::HTTP_UNAUTHORIZED
            );
        }

        $domain = $this->tenantContext->getDomain();
        $this->abilities = ['dashboard', $domain];
        $token = $this->generateToken($user, 'dashboard');
        $data = $this->respondWithToken($user, $token);

        return ApiResponse::success($data, __('auth.loggedIn'));
    }

    public function checkUser($credentials)
    {
        $user = Admin::whereEmail($credentials['email'])->first();

        if ($user && ! Hash::check($credentials['password'], $user->password)) {
            return false;
        }

        return $user;
    }
}
