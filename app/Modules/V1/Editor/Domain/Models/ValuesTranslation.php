<?php

namespace App\Modules\V1\Editor\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class ValuesTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'value_translations';

    protected $fillable = ['value'];
}
