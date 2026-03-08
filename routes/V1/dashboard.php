<?php

use App\Modules\V1\Admins\Presentation\Http\Controllers\AdminAuthController;
use App\Modules\V1\Admins\Presentation\Http\Controllers\AdminManagementController;
use App\Modules\V1\Admins\Presentation\Http\Controllers\RoleManagementController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AdminAuthController::class, 'login']);

Route::middleware(['auth:admins', 'domainAccess'])->group(function () {
    Route::delete('logout', [AdminAuthController::class, 'logout']);

    // Admin management
    Route::get('admins', [AdminManagementController::class, 'index']);
    Route::post('admins', [AdminManagementController::class, 'store']);
    Route::patch('admins/{admin}', [AdminManagementController::class, 'update']);
    Route::delete('admins/{admin}', [AdminManagementController::class, 'destroy']);

    // Role & permission management
    Route::get('permissions', [RoleManagementController::class, 'permissions']);
    Route::get('roles', [RoleManagementController::class, 'index']);
    Route::post('roles', [RoleManagementController::class, 'store']);
    Route::get('roles/{role}', [RoleManagementController::class, 'show']);
    Route::put('roles/{role}', [RoleManagementController::class, 'update']);
    Route::delete('roles/{role}', [RoleManagementController::class, 'destroy']);
});
