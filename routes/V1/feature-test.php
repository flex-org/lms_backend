<?php

use App\Facades\ApiResponse;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admins', 'domainAccess', 'featureAccess:builder'])
    ->get('feature-protected', function () {
        return ApiResponse::success(['ok' => true]);
    });

