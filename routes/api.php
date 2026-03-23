<?php

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Load versioned API routes
foreach (File::files(__DIR__ . '/V1') as $routeFile) {
    require $routeFile->getPathname();
}

Route::get('/platform/{platform}/restructure', [
        \App\Modules\V1\Editor\Application\UseCases\InitializePlatformBuilderUseCase::class,
        'execute'
    ]);
