<?php

use App\Modules\V1\Editor\Presentation\Http\Controllers\PlatformPageController;
use App\Modules\V1\Editor\Presentation\Http\Controllers\PlatformSectionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admins', 'domainAccess', 'featureAccess:builder'])->group(function () {
    Route::get('pages', [PlatformPageController::class, 'index']);
    Route::get('pages/{platformPage}', [PlatformPageController::class, 'show']);
//    Route::patch('pages/{platformPage}', [PlatformPageController::class, 'update']);

    Route::get('pages/{pageKey}/sections', [PlatformSectionController::class, 'index']);
    Route::put('sections/{platformSection}/values', [PlatformSectionController::class, 'updateValues']);
    Route::patch('sections/{platformSection}', [PlatformSectionController::class, 'update']);
    Route::post('pages/{pageKey}/sections/reorder', [PlatformSectionController::class, 'reorder']);
});
