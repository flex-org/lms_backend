<?php

namespace App\Modules\V1\Billing\Domain\Models;

use App\Modules\V1\Billing\Domain\Enums\InvoiceItemType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'type',
        'label',
        'quantity',
        'unit_price',
        'amount',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'type' => InvoiceItemType::class,
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}

