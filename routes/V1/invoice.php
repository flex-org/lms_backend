<?php

use App\Modules\V1\Billing\Presentation\Http\Controllers\InvoiceController;
use \App\Modules\V1\Platforms\Presentation\Http\Controllers\PlatformController;
use Illuminate\Support\Facades\Route;

Route::middleware(['domainExists'])->group(function () {

    Route::get('', [InvoiceController::class, 'index']);
    Route::get('{invoice}', [InvoiceController::class, 'show']);
    Route::post('{invoice}/pay', [InvoiceController::class, 'pay']);
});

