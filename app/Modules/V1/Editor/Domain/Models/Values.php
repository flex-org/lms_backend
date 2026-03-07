<?php

namespace App\Modules\V1\Editor\Domain\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Values extends Model
{
    use Translatable;

    protected $table = 'values';

    protected $fillable = ['platform_section_id', 'structure_id'];

    public $translatedAttributes = ['value'];

    public $translationForeignKey = 'value_id';

    public function platformSection(): BelongsTo
    {
        return $this->belongsTo(PlatformSection::class);
    }

    public function sectionStructure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }
}
