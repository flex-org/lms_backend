<?php

use App\Modules\V1\Categories\Presentation\Http\Controllers\Portal\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['domainExists'])
    ->group(function () {
        Route::get('', [CategoryController::class, 'index']);
        Route::get('{category}', [CategoryController::class, 'show']);
        Route::get('{category}/courses', [CategoryController::class, 'courses']);
    });
