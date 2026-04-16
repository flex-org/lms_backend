<?php

namespace App\Modules\V1\Features\Domain\Models;

use App\Modules\V1\Features\Domain\Enums\DynamicFeaturesValue;
use Illuminate\Database\Eloquent\Model;

class DynamicFeatures extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'price',
    ];

    protected $casts = [
        'name' => DynamicFeaturesValue::class,
    ];

    public function quantityPrice(DynamicFeaturesValue $name, $quantity)
    {
        $model = $this->where('name', $name)->first();
        return $model->price * max(1, $quantity / $model->quantity);
    }
}
