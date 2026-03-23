<?php

use App\Modules\V1\Billing\Presentation\Http\Controllers\InvoiceController;
use App\Modules\V1\Billing\Presentation\Http\Controllers\PlatformSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admins', 'domainAccess'])->group(function () {
    Route::post('platform/upgrades/feature', [PlatformSubscriptionController::class, 'requestAddFeature']);
    Route::post('platform/upgrades/storage', [PlatformSubscriptionController::class, 'requestStorageUpgrade']);
    Route::post('platform/upgrades/capacity', [PlatformSubscriptionController::class, 'requestCapacityUpgrade']);
    Route::post('platform/upgrades/mobile', [PlatformSubscriptionController::class, 'requestEnableMobile']);

    Route::delete('platform/features/{feature}', [PlatformSubscriptionController::class, 'removeFeature']);
    Route::patch('platform/features/{featureId}/deactivate', [PlatformSubscriptionController::class, 'deactivateFeature']);
    Route::patch('platform/features/{featureId}/activate', [PlatformSubscriptionController::class, 'activateFeature']);

    Route::patch('platform/storage/reduce', [PlatformSubscriptionController::class, 'reduceStorage']);
    Route::patch('platform/capacity/reduce', [PlatformSubscriptionController::class, 'reduceCapacity']);
    Route::delete('platform/mobile', [PlatformSubscriptionController::class, 'disableMobile']);

    Route::get('platform/invoices', [InvoiceController::class, 'index']);
    Route::get('platform/invoices/{invoice}', [InvoiceController::class, 'show']);
    Route::post('platform/invoices/{invoice}/pay', [InvoiceController::class, 'pay']);
});

