<?php

namespace App\Modules\V1\Users\Application\Services;

use App\Facades\ApiResponse;
use App\Models\V1\User;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Utilities\Support\Services\AuthWithVerifiedRegisterServices;
use App\Modules\V1\Utilities\Support\Services\OtpService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAuthServices extends AuthWithVerifiedRegisterServices
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

        if (is_null($user->email_verified_at)) {
            $this->abilities = ['portal', $domain, 'not-verified'];
            $token = $this->generateToken($user, 'portal');
            $data = $this->respondWithToken($user, $token);

            return ApiResponse::apiFormat(
                ['data' => $data],
                'use the code that sent to your mail to verify your account',
                Response::HTTP_FORBIDDEN
            );
        }

        $this->abilities = ['portal', $domain, 'verified'];
        $token = $this->generateToken($user, 'portal');
        $data = $this->respondWithToken($user, $token);

        return ApiResponse::success($data, __('auth.login_success'));
    }

    public function signUp(array $userData, OtpService $otpService)
    {
        return DB::transaction(function () use ($userData, $otpService) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'password' => $userData['password'],
            ]);

            $domain = $this->tenantContext->getDomain();
            $this->abilities = ['portal', $domain, 'not-verified'];
            $token = $this->generateToken($user, 'portal');

            return ApiResponse::success([
                'token' => $token,
                'user' => $user,
            ], __('auth.verification_sent'));
        });
    }

    public function verifyEmail(array $data, $user, OtpService $otpService)
    {
        if ($user->email_verified_at) {
            return ApiResponse::validationError([
                'email' => __('auth.already_verified'),
            ]);
        }

        if (! $otpService->validate($user->email, $data['otp'])) {
            return ApiResponse::validationError([
                'otp' => __('auth.invalid_otp'),
            ]);
        }

        $user->update(['email_verified_at' => now()]);
        $user->tokens()->delete();

        $domain = $this->tenantContext->getDomain();
        $this->abilities = ['portal', $domain, 'verified'];
        $token = $this->generateToken($user, 'portal');
        $data = $this->respondWithToken($user, $token);

        return ApiResponse::success($data, __('auth.verified_success'));
    }

    public function resendOtp(array $data, OtpService $otpService)
    {
        $user = User::where('email', $data['email'])->firstOrFail();

        return ApiResponse::message(__('auth.verification_resent'));
    }

    public function forgotPassword(string $email, OtpService $otpService)
    {
        $user = User::where('email', $email)->firstOrFail();

        $otpService->generate($user->email);

        return ApiResponse::message(__('auth.verification_sent'));
    }

    public function verifyResetOtp(array $data, OtpService $otpService)
    {
        $user = User::where('email', $data['email'])->firstOrFail();

        if (! $otpService->validate($user->email, $data['otp'])) {
            return ApiResponse::validationError([
                'otp' => __('auth.invalid_otp'),
            ]);
        }

        $user->tokens()->delete();

        $domain = $this->tenantContext->getDomain();
        $this->abilities = ['reset-password', $domain];
        $token = $this->generateToken($user, 'reset-password');

        return ApiResponse::success(['access_token' => $token]);
    }

    public function resetPassword(string $newPassword, $user)
    {
        $user->update(['password' => Hash::make($newPassword)]);

        $user->currentAccessToken()->delete();

        $domain = $this->tenantContext->getDomain();
        $this->abilities = ['portal', $domain, 'verified'];
        $token = $this->generateToken($user, 'portal');
        $data = $this->respondWithToken($user, $token);

        return ApiResponse::success($data, __('auth.password_reset_success'));
    }

    public function checkUser($credentials)
    {
        $user = User::whereEmail($credentials['email'])->first();

        if ($user && ! Hash::check($credentials['password'], $user->password)) {
            return false;
        }

        return $user;
    }
}
