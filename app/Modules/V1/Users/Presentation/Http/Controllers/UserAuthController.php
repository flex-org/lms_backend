<?php

namespace App\Modules\V1\Users\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\V1\Utilities\Support\Services\OtpService;
use App\Modules\V1\Users\Application\Services\UserAuthServices;
use App\Modules\V1\Utilities\Presentation\Http\Requests\OtpCheckRequest;
use App\Modules\V1\Utilities\Presentation\Http\Requests\EmailVerificationRequest;
use App\Modules\V1\Utilities\Support\Contracts\Auth\AuthenticatableWithVerifiedRegisterInterface;
use App\Modules\V1\Utilities\Presentation\Http\Requests\LoginRequest;

class UserAuthController extends Controller implements AuthenticatableWithVerifiedRegisterInterface
{
    public function __construct(public UserAuthServices $authServices){}

    public function login(LoginRequest $loginRequest)
    {   
        return $this->authServices->login($loginRequest->only(['email','password']));
    }
    
    public function logout(Request $request)
    {
        return $this->authServices->logout($request);
    }

    public function signUp(Request $signUpRequest, OtpService $otpService)
    {
        $validatedData = $signUpRequest->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users",
            'phone' => "required|string|unique:users",
            'password' => 'required|string|min:7|confirmed',
        ]);

        return $this->authServices->signUp(
            $validatedData, 
            $otpService
        );

    }

    public function verifyEmail(OtpCheckRequest $request, OtpService $otpService)
    {
        return $this->authServices->verifyEmail(
            $request->validated(),
            $request->user(),
            $otpService
        );
    }

    public function resendOtp(EmailVerificationRequest $request, OtpService $otpService)
    {
        return $this->authServices->resendOtp(
            $request->validated(), 
            $otpService
        );
    }
    
}
