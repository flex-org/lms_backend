<?php

namespace App\Modules\V1\Users\Application\Services;

use App\Models\V1\User;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Platforms\Application\UseCases\GetPlatformOverViewUseCase;
use App\Modules\V1\Utilities\Support\Services\OtpService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserAuthService
{
    public function __construct(
        private readonly TenantContextInterface $tenantContext,
    ) {
    }

    public function login($credentials, GetPlatformOverViewUseCase $getPlatformOverViewUseCase)
    {
        if (! $user = $this->checkUser($credentials))
            throw new UnauthorizedHttpException('', __('auth.credentials_mismatch'));

        $abilities = [
            'portal',
            $this->tenantContext->getDomain(),
            is_null($user->email_verified_at) ? 'not-verified' : 'verified'
        ];

        $platformOverView = $getPlatformOverViewUseCase->execute(
            $this->tenantContext->getPlatform()
        );

        return array_merge($platformOverView,[
            'access_token' => $user->createToken('portal', $abilities)->plainTextToken ,
            'user' => $user,
            'verified' => !is_null($user->email_verified_at),
        ]);

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
            $abilities = ['portal', $domain, 'not-verified'];
            $token = $user->createToken('portal', $abilities)->plainTextToken;

            return [
                'token' => $token,
                'user' => $user,
                'verified' => false,
            ];
        });
    }

    public function verifyEmail(array $data, User $user, OtpService $otpService, GetPlatformOverViewUseCase $getPlatformOverViewUseCase)
    {
        if ($user->email_verified_at)
            throw new BadRequestHttpException(__('auth.already_verified'));

        if (! $otpService->validate($user->email, $data['otp']))
            throw new BadRequestHttpException(__('auth.invalid_otp'));


        $user->update(['email_verified_at' => now()]);
        $user->tokens()->delete();

        $abilities = [
            'portal',
            $this->tenantContext->getDomain(),
            'verified'
        ];

        $platformOverView = $getPlatformOverViewUseCase->execute(
            $this->tenantContext->getPlatform()
        );

        return array_merge($platformOverView,[
            'access_token' => $user->createToken('portal', $abilities)->plainTextToken,
            'user' => $user,
            'verified' => true,
        ]);
    }

    public function generateOtpForEmail(string $email, OtpService $otpService) : void
    {
        $user = User::where('email', $email)->first();
        if($user)
            $otpService->generate($user->email);
    }

    public function verifyResetOtp(array $data, OtpService $otpService) : array
    {
        $user = User::where('email', $data['email'])->firstOrFail();

        if (! $otpService->validate($user->email, $data['otp']))
            throw new BadRequestHttpException(__('auth.invalid_otp'));

        $user->tokens()->delete();

        $abilities = ['reset-password', $this->tenantContext->getDomain()];
        $token = $user->createToken('reset-password', $abilities)->plainTextToken;

        return ['access_token' => $token];
    }

    public function resetPassword(string $newPassword, User $user) : void
    {
        $user->update(['password' => Hash::make($newPassword)]);
        $user->tokens()->delete();
    }

    public function checkUser(array $credentials) : ?User
    {
        $user = User::whereEmail($credentials['email'])->first();
        return ($user && Hash::check($credentials['password'], $user->password)) ? $user : null;
    }

}
