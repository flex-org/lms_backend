<?php

namespace App\Modules\V1\Features\Domain\Models;

use Illuminate\Database\Eloquent\Model;
class FeatureTranslation extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description'
    ];
}
