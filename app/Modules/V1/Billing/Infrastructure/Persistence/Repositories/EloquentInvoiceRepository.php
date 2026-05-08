<?php

namespace App\Modules\V1\Billing\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Billing\Domain\Enums\InvoiceItemType;
use App\Modules\V1\Billing\Domain\Enums\InvoiceStatus;
use App\Modules\V1\Billing\Domain\Enums\InvoiceType;
use App\Modules\V1\Billing\Domain\Models\Invoice;
use App\Modules\V1\Billing\Domain\Models\InvoiceItem;
use App\Modules\V1\Billing\Domain\Repositories\InvoiceRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Collection;

class EloquentInvoiceRepository implements InvoiceRepositoryInterface
{
    public function create(array $attributes): Invoice
    {
        return Invoice::create($attributes);
    }

    public function createProration(
        Platform $platform,
        InvoiceItemType $type,
        string $label,
        int $amount,
        int $quantity,
        int $unitPrice
    ): Invoice
    {
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
            'type' => $type,
            'label' => $label,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'amount' => $amount,
            'period_start' => now()->toDateString(),
            'period_end' => $platform->renew_at,
        ]);

        return $invoice;
    }

    public function find(int $id): ?Invoice
    {
        return Invoice::find($id);
    }

    public function forPlatform(Platform $platform): Collection
    {
        return $platform->invoices()->latest()->get();
    }
}

