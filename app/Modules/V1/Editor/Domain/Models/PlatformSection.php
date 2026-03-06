<?php

namespace App\Modules\V1\Editor\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlatformSection extends Model
{
    protected $fillable = ['platform_page_id', 'section_id', 'active', 'position'];

    protected $casts = [
        'active' => 'boolean',
        'position' => 'integer',
    ];

    public function platformPage(): BelongsTo
    {
        return $this->belongsTo(PlatformPage::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function sectionValues(): HasMany
    {
        return $this->hasMany(Values::class);
    }
}
