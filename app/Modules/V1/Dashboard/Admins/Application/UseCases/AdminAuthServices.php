<?php
namespace App\Modules\V1\Dashboard\Admins\Application\UseCases;

use App\Facades\ApiResponse;
use App\Modules\V1\Dashboard\Admins\Domain\Models\Admin;
use App\Modules\V1\Utilities\Support\Services\AuthServices;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AdminAuthServices extends AuthServices
{
    public function login($credentials)
    {
        if (!$user = $this->checkUser($credentials)) {
            return ApiResponse::message(
                'Your credentials doesn\'t match our records',
                Response::HTTP_UNAUTHORIZED
            );
        }
        $domain = request()->header('domain');
        $this->abilities = ['dashboard', $domain];
        $token = $this->generateToken($user, 'dashboard');
        $data = $this->respondWithToken($user, $token);

        return ApiResponse::success($data, __('auth.loggedIn'));
    }

    function checkUser($credentials)
    {
        $user = Admin::whereEmail($credentials['email'])->first();
        if ($user && !Hash::check($credentials['password'], $user->password))
            return false;
        return $user;
    }
}
