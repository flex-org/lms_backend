<?php

use App\Modules\V1\Billing\Presentation\Http\Controllers\InvoiceController;
use App\Modules\V1\Billing\Presentation\Http\Controllers\PlatformSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['domainExists'])->group(function () {
    Route::post('platform/add/{feature}', [PlatformSubscriptionController::class, 'requestAddFeature']);
    Route::delete('platform/remove/{feature}', [PlatformSubscriptionController::class, 'removeFeature']);
    Route::patch('platform/features/{featureId}/deactivate', [PlatformSubscriptionController::class, 'deactivateFeature']);
    Route::patch('platform/features/{featureId}/activate', [PlatformSubscriptionController::class, 'activateFeature']);

    Route::post('platform/updates/storage', [PlatformSubscriptionController::class, 'requestStorageUpdate']);
    Route::post('platform/updates/capacity', [PlatformSubscriptionController::class, 'requestCapacityUpdate']);
    Route::post('platform/updates/mobile', [PlatformSubscriptionController::class, 'requestToggleMobile']);

    Route::get('platform/invoices', [InvoiceController::class, 'index']);
    Route::get('platform/invoices/{invoice}', [InvoiceController::class, 'show']);
    Route::post('platform/invoices/{invoice}/pay', [InvoiceController::class, 'pay']);
});

