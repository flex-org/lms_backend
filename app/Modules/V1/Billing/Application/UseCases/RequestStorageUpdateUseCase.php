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
use App\Modules\V1\Features\Domain\Enums\DynamicFeaturesValue;
use App\Modules\V1\Features\Domain\Models\DynamicFeatures;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Facades\DB;

final readonly class RequestStorageUpdateUseCase
{
    public function __construct(
        private ProrationService $prorationService,
        private DynamicFeatures $storageFeature,
    ) {
    }

    public function execute(Platform $platform, int $newStorage): ?PlatformPendingChange
    {
        if ($newStorage <= $platform->storage) {
            throw new \DomainException(__('billing.storage_must_differ'));
        }

        $oldPrice = $this->storageFeature->quantityPrice(DynamicFeaturesValue::STORAGE, $platform->storage);
        $newPrice = $this->storageFeature->quantityPrice(DynamicFeaturesValue::STORAGE, $newStorage);

        return ($newStorage < $platform->storage)
            ? $this->reduceStorage($platform, $newStorage, $oldPrice, $newPrice)
            : $this->increaseStorage($platform, $newStorage, $oldPrice, $newPrice);
    }

    private function reduceStorage($platform, $newStorage, $oldPrice, $newPrice)
    {
        return DB::transaction(function () use ($platform, $newStorage, $oldPrice, $newPrice) {
            $diff = max(0, $oldPrice - $newPrice);
            $platform->storage = $newStorage;
            $platform->cost = max(0, (float) $platform->cost - $diff);
            $platform->save();
        });
    }

    private function increaseStorage($platform, $newStorage, $oldPrice, $newPrice)
    {
        return DB::transaction(function () use ($platform, $newStorage, $oldPrice, $newPrice) {
            $daysRemaining = $this->prorationService->daysRemaining($platform);

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

            $pendingChange = PlatformPendingChange::create([
                'platform_id' => $platform->id,
                'invoice_id' => $invoice->id,
                'change_type' => PendingChangeType::INCREASE_STORAGE,
                'payload' => [
                    'old_storage' => $platform->storage,
                    'new_storage' => $newStorage,
                ],
                'status' => PendingChangeStatus::PENDING,
            ]);
            $this->temporaryActivation($invoice, $pendingChange);
            return $pendingChange;
        });
    }

    private function temporaryActivation($invoice, $pendingChange)
    {
        $invoice->status = InvoiceStatus::PAID;
        $invoice->paid_at = now();
        $invoice->save();

        (new ApplyPendingChangeUseCase)->execute($pendingChange);
    }
}

