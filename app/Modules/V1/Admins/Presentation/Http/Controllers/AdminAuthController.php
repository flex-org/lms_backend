<?php
namespace App\Modules\V1\Admins\Presentation\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\V1\Admins\Application\Services\AdminAuthServices;
use App\Modules\V1\Utilities\Presentation\Http\Requests\LoginRequest;
use App\Modules\V1\Utilities\Support\Contracts\Auth\AuthenticatableInterface;
use Illuminate\Http\Request;

class AdminAuthController extends Controller implements AuthenticatableInterface
{
    public function __construct(public AdminAuthServices $authServices) {}

    public function login(LoginRequest $loginRequest)
    {
        return $this->authServices->login($loginRequest->validated());
    }

    public function logout(Request $request)
    {
        return $this->authServices->logout($request);
    }

}
