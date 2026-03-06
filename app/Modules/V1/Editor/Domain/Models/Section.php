<?php

namespace App\Modules\V1\Editor\Domain\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use Translatable;

    protected $fillable = ['page_id'];

    public $translatedAttributes = ['name'];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function structures(): HasMany
    {
        return $this->hasMany(Structure::class);
    }
}
