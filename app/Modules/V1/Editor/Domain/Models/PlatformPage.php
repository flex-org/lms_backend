<?php

namespace App\Modules\V1\Editor\Domain\Models;

use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlatformPage extends Model
{
    protected $fillable = ['platform_id', 'page_id', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function platformSections(): HasMany
    {
        return $this->hasMany(PlatformSection::class)->orderBy('position');
    }
}
