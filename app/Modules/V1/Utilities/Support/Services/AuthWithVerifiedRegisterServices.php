<?php

namespace App\Modules\V1\Utilities\Support\Services;

use App\Modules\V1\Utilities\Support\Services\OtpService;

Abstract class AuthWithVerifiedRegisterServices extends AuthServices
{

    abstract function signUp(array $userData, OtpService $otpService);

    abstract function verifyEmail(array $data, $user, OtpService $otpService);

    abstract function resendOtp(array $data, OtpService $otpService);
}
