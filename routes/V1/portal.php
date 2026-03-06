<?php

use App\Modules\V1\Users\Presentation\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [UserAuthController::class, 'login']);
Route::post('signup', [UserAuthController::class, 'signUp']);
Route::post('resend-otp', [UserAuthController::class, 'resendOtp']);
Route::post('verify-email', [UserAuthController::class, 'verifyEmail'])
    ->middleware(['auth:sanctum', 'abilities:not-verified']);

Route::middleware(['auth:sanctum', 'domainAccess'])->group(function () {
    Route::delete('logout', [UserAuthController::class, 'logout']);
});
