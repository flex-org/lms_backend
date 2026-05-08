<?php

namespace App\Modules\V1\Billing\Domain\Repositories;

use App\Modules\V1\Billing\Domain\Enums\InvoiceItemType;
use App\Modules\V1\Billing\Domain\Models\Invoice;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Collection;

interface InvoiceRepositoryInterface
{
    public function create(array $attributes): Invoice;
    public function createProration(
        Platform $platform,
        InvoiceItemType $type,
        string $label,
        int $amount,
        int $quantity,
        int $unitPrice
    ): Invoice;
    public function find(int $id): ?Invoice;

    public function forPlatform(Platform $platform): Collection;
}

