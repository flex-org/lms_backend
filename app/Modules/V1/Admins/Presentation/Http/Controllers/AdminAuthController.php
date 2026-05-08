<?php
namespace App\Modules\V1\Admins\Presentation\Http\Controllers;


use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\Admins\Application\Services\AdminAuthService;
use App\Modules\V1\Admins\Presentation\Http\Resources\AdminResource;
use App\Modules\V1\Platforms\Application\UseCases\GetPlatformOverViewUseCase;
use App\Modules\V1\Platforms\Presentation\Http\Resources\PlatformFeatureResource;
use App\Modules\V1\Platforms\Presentation\Http\Resources\PlatformSellingSystemResource;
use App\Modules\V1\Themes\Presentation\Http\Resources\ThemeResource;
use App\Modules\V1\Utilities\Presentation\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function login(LoginRequest $loginRequest, AdminAuthService $authServices, GetPlatformOverViewUseCase $getPlatformOverViewUseCase)
    {
        $data = $authServices->login(
            $loginRequest->validated(), $getPlatformOverViewUseCase);

        return ApiResponse::success([
            'access_token'    => $data['access_token'],
            'admin'           => new AdminResource($data['user']),
            'platform'        => [
                'features'        => PlatformFeatureResource::collection($data['features']),
                'selling_systems' => PlatformSellingSystemResource::collection($data['selling_systems']),
                'theme'           => new ThemeResource($data['theme']),
                'template'        => $data['template']
            ],
        ], __('auth.loggedIn'));
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();
        return ApiResponse::message(__('auth.loggedOut'));
    }


}
