<?php

namespace App\Modules\V1\Editor\Domain\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use Translatable;

    protected $fillable = [];

    public $translatedAttributes = ['name'];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function platformPages(): HasMany
    {
        return $this->hasMany(PlatformPage::class);
    }
}
