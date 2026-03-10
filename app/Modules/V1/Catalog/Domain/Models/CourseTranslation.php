<?php

namespace App\Modules\V1\Catalog\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['title', 'description'];
}
