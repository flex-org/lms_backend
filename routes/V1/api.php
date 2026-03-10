<?php

use App\Modules\V1\Features\Presentation\Http\Controllers\FeatureController;
use App\Modules\V1\Platforms\Presentation\Http\Controllers\PlatformController;
use Illuminate\Support\Facades\Route;

Route::get('/features', [FeatureController::class, 'getActiveFeatures']);
Route::get('/dynamic-features', [FeatureController::class, 'getDynamicFeatures']);

Route::get('/platforms/search', [PlatformController::class, 'search']);

Route::prefix('platform')->group(function () {
    Route::post('create', [PlatformController::class, 'store']);
});
