<?php

use App\Modules\V1\Editor\Presentation\Http\Controllers\PlatformPageController;
use App\Modules\V1\Editor\Presentation\Http\Controllers\PlatformSectionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admins', 'domainAccess', 'featureAccess:builder'])->group(function () {
    Route::get('pages', [PlatformPageController::class, 'index']);
    Route::post('pages', [PlatformPageController::class, 'store']);
    Route::put('pages/{platformPage}', [PlatformPageController::class, 'update']);
    Route::delete('pages/{platformPage}', [PlatformPageController::class, 'destroy']);

    Route::get('pages/{platformPageId}/sections', [PlatformSectionController::class, 'index']);
    Route::post('sections', [PlatformSectionController::class, 'store']);
    Route::put('sections/{platformSection}', [PlatformSectionController::class, 'update']);
    Route::delete('sections/{platformSection}', [PlatformSectionController::class, 'destroy']);
    Route::post('pages/{platformPageId}/sections/reorder', [PlatformSectionController::class, 'reorder']);
});
