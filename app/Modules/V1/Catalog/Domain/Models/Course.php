<?php

namespace App\Modules\V1\Catalog\Domain\Models;

use App\Traits\V1\BelongsToTenant;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Course extends Model implements HasMedia
{
    use BelongsToTenant, Translatable, InteractsWithMedia;

    protected $fillable = ['platform_id', 'category_id', 'price', 'active'];

    protected $casts = [
        'active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public $translatedAttributes = ['title', 'description'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
