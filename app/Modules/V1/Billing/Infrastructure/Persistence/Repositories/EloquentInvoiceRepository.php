<?php

namespace App\Modules\V1\Billing\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Billing\Domain\Models\Invoice;
use App\Modules\V1\Billing\Domain\Repositories\InvoiceRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Collection;

class EloquentInvoiceRepository implements InvoiceRepositoryInterface
{
    public function create(array $attributes): Invoice
    {
        return Invoice::create($attributes);
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

