<?php

namespace App\Modules\V1\Editor\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];
}
