<?php

use App\Modules\V1\Platforms\Presentation\Http\Controllers\PlatformController;
use App\Modules\V1\Users\Presentation\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;


Route::post('login', [UserAuthController::class, 'login']);
Route::post('signup', [UserAuthController::class, 'signUp']);
Route::post('resend-otp', [UserAuthController::class, 'resendOtp']);
Route::post('verify-email', [UserAuthController::class, 'verifyEmail'])
    ->middleware(['auth:sanctum', 'abilities:not-verified']);

Route::post('forgot-password', [UserAuthController::class, 'forgotPassword']);
Route::post('verify-reset-otp', [UserAuthController::class, 'verifyResetOtp']);
Route::post('reset-password', [UserAuthController::class, 'resetPassword'])
    ->middleware(['auth:sanctum', 'abilities:reset-password']);

Route::middleware(['auth:sanctum', 'domainAccess'])->group(function () {
    Route::delete('logout', [UserAuthController::class, 'logout']);
});
