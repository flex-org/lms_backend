<?php

use App\Facades\ApiResponse;
use Illuminate\Support\Facades\Route;

Route::get('mobile-client-ping', function () {
    return ApiResponse::success(['ok' => true]);
});

Route::middleware(['auth:admins', 'domainAccess', 'featureAccess:builder'])
    ->get('feature-protected', function () {
        return ApiResponse::success(['ok' => true]);
    });

