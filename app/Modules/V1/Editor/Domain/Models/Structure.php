<?php

namespace App\Modules\V1\Editor\Domain\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Structure extends Model
{
    use Translatable;

    protected $fillable = ['section_id', 'type', 'name', 'is_array'];

    public $translatedAttributes = ['label', 'placeholder'];

    protected $casts = [
        'is_array' => 'boolean',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
