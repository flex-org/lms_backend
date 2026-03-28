<?php

use App\Modules\V1\Courses\Presentation\Http\Controllers\Portal\CourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['domainExists'])
    ->group(function () {
        Route::get('', [CourseController::class, 'index']);
        Route::get('{course}', [CourseController::class, 'show']);
    });
