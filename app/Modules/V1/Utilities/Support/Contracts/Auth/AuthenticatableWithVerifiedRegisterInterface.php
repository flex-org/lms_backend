<?php

namespace App\Modules\V1\Utilities\Support\Contracts\Auth;

use App\Modules\V1\Utilities\Presentation\Http\Requests\EmailVerificationRequest;
use App\Modules\V1\Utilities\Presentation\Http\Requests\OtpCheckRequest;
use App\Modules\V1\Utilities\Support\Services\OtpService;
use Illuminate\Http\Request;

interface AuthenticatableWithVerifiedRegisterInterface extends AuthenticatableInterface
{
    public function signUp(Request $signUpRequest, OtpService $otpService);

    public function verifyEmail(OtpCheckRequest $request, OtpService $otpService);

    public function resendOtp(EmailVerificationRequest $request, OtpService $otpService);
}