<?php

use App\Modules\V1\Billing\Presentation\Http\Controllers\InvoiceController;
use \App\Modules\V1\Platforms\Presentation\Http\Controllers\PlatformController;
use Illuminate\Support\Facades\Route;

Route::middleware(['domainExists'])->group(function () {

    Route::get('system', [PlatformController::class, 'features']);

    Route::post('add/{feature}', [PlatformController::class, 'requestAddFeature']);
    Route::delete('remove/{feature}', [PlatformController::class, 'removeFeature']);
    Route::patch('features/{featureId}/{status}', [PlatformController::class, 'toggleActivateFeature'])
        ->where('status', 'activate|deactivate');

    Route::post('updates/storage', [PlatformController::class, 'requestStorageUpdate']);
    Route::post('updates/capacity', [PlatformController::class, 'requestCapacityUpdate']);
    Route::post('updates/mobile', [PlatformController::class, 'requestToggleMobile']);

    Route::get('invoices', [InvoiceController::class, 'index']);
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show']);
    Route::post('invoices/{invoice}/pay', [InvoiceController::class, 'pay']);
});

