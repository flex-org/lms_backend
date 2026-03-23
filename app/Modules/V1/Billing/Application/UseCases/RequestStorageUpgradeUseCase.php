<?php

namespace App\Modules\V1\Billing\Application\UseCases;

use App\Modules\V1\Billing\Domain\Enums\InvoiceItemType;
use App\Modules\V1\Billing\Domain\Enums\InvoiceStatus;
use App\Modules\V1\Billing\Domain\Enums\InvoiceType;
use App\Modules\V1\Billing\Domain\Enums\PendingChangeStatus;
use App\Modules\V1\Billing\Domain\Enums\PendingChangeType;
use App\Modules\V1\Billing\Domain\Models\Invoice;
use App\Modules\V1\Billing\Domain\Models\InvoiceItem;
use App\Modules\V1\Billing\Domain\Models\PlatformPendingChange;
use App\Modules\V1\Billing\Domain\Services\ProrationService;
use App\Modules\V1\Features\Domain\Models\DynamicFeatures;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Facades\DB;

final readonly class RequestStorageUpgradeUseCase
{
    public function __construct(
        private ProrationService $prorationService,
        private DynamicFeatures $storageFeature,
    ) {
    }

    public function execute(Platform $platform, int $newStorage): PlatformPendingChange
    {
        if ($newStorage <= $platform->storage) {
            throw new \DomainException('New storage must be greater than current.');
        }

        return DB::transaction(function () use ($platform, $newStorage) {
            $daysRemaining = $this->prorationService->daysRemaining($platform);

            $oldPrice = $this->storageFeature->quantityPrice($platform->storage);
            $newPrice = $this->storageFeature->quantityPrice($newStorage);
            $amount = $this->prorationService->dynamicProration($oldPrice, $newPrice, $daysRemaining);

            $invoice = Invoice::create([
                'platform_id' => $platform->id,
                'type' => InvoiceType::PRORATION,
                'status' => InvoiceStatus::PENDING,
                'amount' => $amount,
                'period_start' => now()->toDateString(),
                'period_end' => $platform->renew_at,
                'due_at' => now()->toDateString(),
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'type' => InvoiceItemType::STORAGE,
                'label' => 'storage',
                'quantity' => $newStorage - $platform->storage,
                'unit_price' => $newPrice - $oldPrice,
                'amount' => $amount,
                'period_start' => now()->toDateString(),
                'period_end' => $platform->renew_at,
            ]);

            return PlatformPendingChange::create([
                'platform_id' => $platform->id,
                'invoice_id' => $invoice->id,
                'change_type' => PendingChangeType::INCREASE_STORAGE,
                'payload' => [
                    'old_storage' => $platform->storage,
                    'new_storage' => $newStorage,
                ],
                'status' => PendingChangeStatus::PENDING,
            ]);
        });
    }
}

