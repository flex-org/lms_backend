<?php

namespace App\Modules\V1\Platforms\Application\UseCases;

use App\Events\ProrationInvoiceCreated;
use App\Modules\V1\Billing\Domain\Enums\InvoiceItemType;
use App\Modules\V1\Billing\Domain\Enums\InvoiceStatus;
use App\Modules\V1\Billing\Domain\Enums\InvoiceType;
use App\Modules\V1\Billing\Domain\Enums\PendingChangeStatus;
use App\Modules\V1\Billing\Domain\Enums\PendingChangeType;
use App\Modules\V1\Billing\Domain\Models\Invoice;
use App\Modules\V1\Billing\Domain\Repositories\InvoiceRepositoryInterface;
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
        private InvoiceRepositoryInterface $invoiceRepository,
    ) {
    }

    public function execute(Platform $platform, int $newStorage): ?Invoice
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
        $daysRemaining = $this->prorationService->daysRemaining($platform);
        $amount = $this->prorationService->dynamicProration(
            $oldPrice,
            $newPrice,
            $daysRemaining
        );

        return DB::transaction(function () use ($platform, $newStorage, $oldPrice, $newPrice, $amount) {

            $invoice = $this->invoiceRepository->createProration(
                platform: $platform,
                type: InvoiceItemType::STORAGE,
                label: 'storage',
                amount: $amount,
                quantity: $newStorage - $platform->storage,
                unitPrice: $newPrice - $oldPrice,
            );

            ProrationInvoiceCreated::dispatch(
                $platform,
                $invoice,
                PendingChangeType::INCREASE_STORAGE,
                [
                    'old_storage' => $platform->storage,
                    'new_storage' => $newStorage,
                ]
            );

            return $invoice;
        });
    }

}

