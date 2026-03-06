<?php

namespace App\Traits\V1;

use App\Models\V1\Scopes\PlatformScope;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new PlatformScope);

        static::creating(function ($model) {
            if (! empty($model->platform_id)) {
                return;
            }

            $platformId = app(TenantContextInterface::class)->getPlatformId();

            if ($platformId !== null) {
                $model->platform_id = $platformId;
            }
        });
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    public function getPlatformId(): int
    {
        return $this->platform_id;
    }
}
