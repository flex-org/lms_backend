<?php

namespace App\Modules\V1\Users\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\V1\Users\Application\Services\UserAuthServices;
use App\Modules\V1\Users\Presentation\Http\Requests\ForgotPasswordRequest;
use App\Modules\V1\Users\Presentation\Http\Requests\ResetPasswordRequest;
use App\Modules\V1\Users\Presentation\Http\Requests\SignupRequest;
use App\Modules\V1\Users\Presentation\Http\Requests\VerifyResetOtpRequest;
use App\Modules\V1\Utilities\Presentation\Http\Requests\LoginRequest;
use App\Modules\V1\Utilities\Presentation\Http\Requests\OtpCheckRequest;
use App\Modules\V1\Utilities\Presentation\Http\Requests\EmailVerificationRequest;
use App\Modules\V1\Utilities\Support\Services\OtpService;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    public function __construct(public UserAuthServices $authServices)
    {
    }

    public function login(LoginRequest $loginRequest)
    {
        return $this->authServices->login($loginRequest->only(['email', 'password']));
    }

    public function logout(Request $request)
    {
        return $this->authServices->logout($request);
    }

    public function signUp(SignupRequest $request, OtpService $otpService)
    {
        return $this->authServices->signUp($request->validated(), $otpService);
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

    public function forgotPassword(ForgotPasswordRequest $request, OtpService $otpService)
    {
        return $this->authServices->forgotPassword(
            $request->validated('email'),
            $otpService,
        );
    }

    public function verifyResetOtp(VerifyResetOtpRequest $request, OtpService $otpService)
    {
        return $this->authServices->verifyResetOtp(
            $request->validated(),
            $otpService,
        );
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        return $this->authServices->resetPassword(
            $request->validated('password'),
            $request->user(),
        );
    }
}
