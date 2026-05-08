<?php

namespace App\Modules\V1\Users\Presentation\Http\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\Platforms\Application\UseCases\GetPlatformOverViewUseCase;
use App\Modules\V1\Platforms\Presentation\Http\Resources\PlatformFeatureResource;
use App\Modules\V1\Platforms\Presentation\Http\Resources\PlatformSellingSystemResource;
use App\Modules\V1\Themes\Presentation\Http\Resources\ThemeResource;
use App\Modules\V1\Users\Application\Services\UserAuthService;
use App\Modules\V1\Users\Presentation\Http\Requests\ResetPasswordRequest;
use App\Modules\V1\Users\Presentation\Http\Requests\SignupRequest;
use App\Modules\V1\Users\Presentation\Http\Requests\VerifyResetOtpRequest;
use App\Modules\V1\Users\Presentation\Http\Resources\UserResource;
use App\Modules\V1\Utilities\Presentation\Http\Requests\LoginRequest;
use App\Modules\V1\Utilities\Presentation\Http\Requests\OtpCheckRequest;
use App\Modules\V1\Utilities\Presentation\Http\Requests\EmailVerificationRequest;
use App\Modules\V1\Utilities\Support\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserAuthController extends Controller
{
    public function __construct(public UserAuthService $authServices)
    {
    }

    public function login(LoginRequest $loginRequest, GetPlatformOverViewUseCase $getPlatformOverViewUseCase)
    {
        $data = $this->authServices->login(
            $loginRequest->validated(),
            $getPlatformOverViewUseCase
        );

        return ($data['verified']) ?
            ApiResponse::success(
                data: $this->buildAuthResponse($data),
                message: __('auth.loggedIn')
            ) :
            ApiResponse::apiFormat(
                info: ['data' => $this->buildAuthResponse($data)],
                message: __('auth.verify_account'),
                code: Response::HTTP_FORBIDDEN
            );
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();
        return ApiResponse::message(__('auth.loggedOut'));
    }

    public function signUp(SignupRequest $request, OtpService $otpService)
    {
        $data = $this->authServices->signUp($request->validated(), $otpService);

        return ApiResponse::success([
            'token' => $data['token'],
            'user' => new UserResource($data['user']),
        ], __('auth.verification_sent'));
    }

    public function verifyEmail(OtpCheckRequest $request, OtpService $otpService, GetPlatformOverViewUseCase $getPlatformOverViewUseCase)
    {
        $data = $this->authServices->verifyEmail(
            $request->validated(),
            $request->user(),
            $otpService,
            $getPlatformOverViewUseCase
        );

        return ApiResponse::success(
            data: $this->buildAuthResponse($data),
            message: __('auth.verified_success')
        );
    }

    public function generateOtpForEmail(EmailVerificationRequest $request, OtpService $otpService)
    {
        $this->authServices->generateOtpForEmail(
            $request->validated('email'),
            $otpService
        );
        return ApiResponse::message(__('auth.code_sent'));
    }

    public function verifyResetPassOtp(VerifyResetOtpRequest $request, OtpService $otpService)
    {
        $token = $this->authServices->verifyResetOtp(
            $request->validated(),
            $otpService,
        );
        return ApiResponse::success($token);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
         $this->authServices->resetPassword(
            $request->validated('password'),
            $request->user(),
        );

        return ApiResponse::message(__('auth.password_reset_success'));
    }


    private function buildAuthResponse(array $data): array
    {
        return [
            'access_token' => $data['access_token'],
            'user' => new UserResource($data['user']),
            'platform' => [
                'features' => PlatformFeatureResource::collection($data['features']),
                'selling_systems' => PlatformSellingSystemResource::collection($data['selling_systems']),
                'theme' => new ThemeResource($data['theme']),
                'template' => $data['template']
            ],
        ];
    }
}
