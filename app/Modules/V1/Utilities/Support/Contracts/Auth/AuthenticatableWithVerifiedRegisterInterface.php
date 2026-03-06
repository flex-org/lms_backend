<?php 
namespace App\Modules\V1\Utilities\Support\Contracts\Auth;

use Illuminate\Http\Request;
use App\Modules\V1\Utilities\Support\Services\OtpService;
use App\Modules\V1\Utilities\Presentation\Http\Requests\OtpCheckRequest;
use App\Modules\V1\Utilities\Presentation\Http\Requests\EmailVerificationRequest;

interface AuthenticatableWithVerifiedRegisterInterface extends AuthenticatableInterface
{
    public function signUp(Request $signUpRequest, OtpService $otpService);

    public function verifyEmail(OtpCheckRequest $request, OtpService $otpService);

    public function resendOtp(EmailVerificationRequest $request, OtpService $otpService);
}