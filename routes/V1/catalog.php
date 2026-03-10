<?php

use App\Modules\V1\Catalog\Presentation\Http\Controllers\Portal\CategoryController;
use App\Modules\V1\Catalog\Presentation\Http\Controllers\Portal\CourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['domainExists'])->group(function () {
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);
    Route::get('categories/{category}/courses', [CategoryController::class, 'courses']);
    Route::get('courses', [CourseController::class, 'index']);
    Route::get('courses/{course}', [CourseController::class, 'show']);
});
