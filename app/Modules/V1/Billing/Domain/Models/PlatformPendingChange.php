<?php

namespace App\Modules\V1\Billing\Domain\Models;

use App\Modules\V1\Billing\Domain\Enums\PendingChangeStatus;
use App\Modules\V1\Billing\Domain\Enums\PendingChangeType;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformPendingChange extends Model
{
    protected $fillable = [
        'platform_id',
        'invoice_id',
        'change_type',
        'payload',
        'status',
    ];

    protected $casts = [
        'change_type' => PendingChangeType::class,
        'status' => PendingChangeStatus::class,
        'payload' => 'array',
    ];

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}

