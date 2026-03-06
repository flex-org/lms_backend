<?php

namespace App\Modules\V1\Themes\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'name', 
        'color',
        'colors',
        'price'
    ];

    protected $casts = [
        'colors' => 'array',
    ];
}
