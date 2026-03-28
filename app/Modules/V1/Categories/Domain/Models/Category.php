<?php

namespace App\Modules\V1\Catalog\Domain\Models;

use App\Traits\V1\BelongsToTenant;
use Astrotomic\Translatable\Translatable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use BelongsToTenant, Translatable, InteractsWithMedia, Filterable;

    protected $fillable = [
        'platform_id',
        'price',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public $translatedAttributes = ['name', 'description'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
