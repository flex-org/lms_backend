<?php

namespace App\Modules\V1\Billing\Domain\Models;

use App\Modules\V1\Billing\Domain\Enums\InvoiceStatus;
use App\Modules\V1\Billing\Domain\Enums\InvoiceType;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    protected $fillable = [
        'platform_id',
        'type',
        'status',
        'amount',
        'period_start',
        'period_end',
        'due_at',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'type' => InvoiceType::class,
        'status' => InvoiceStatus::class,
        'period_start' => 'date',
        'period_end' => 'date',
        'due_at' => 'date',
        'paid_at' => 'datetime',
    ];

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function pendingChange(): HasOne
    {
        return $this->hasOne(PlatformPendingChange::class);
    }
}

