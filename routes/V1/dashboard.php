<?php

use App\Modules\V1\Admins\Presentation\Http\Controllers\AdminAuthController;
use App\Modules\V1\Admins\Presentation\Http\Controllers\AdminManagementController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AdminAuthController::class, 'login']);

Route::group(['middleware' => ['locale', 'auth:admins', 'domainAccess']], function () {
    Route::delete('logout', [AdminAuthController::class, 'logout']);

    // Admin management
    Route::get('admins', [AdminManagementController::class, 'index']);
    Route::post('admins', [AdminManagementController::class, 'store']);
    Route::put('admins/{admin}', [AdminManagementController::class, 'update']);
    Route::delete('admins/{admin}', [AdminManagementController::class, 'destroy']);
});
