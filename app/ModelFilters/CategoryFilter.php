<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class CategoryFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function active(bool $value)
    {
        return $this->where('active', $value);
    }

    public function name(string $name)
    {
        return $this->whereTranslationLike('name', "%{$name}%");
    }

    public function minPrice(int $min)
    {
        return $this->where('price', '>=', $min);
    }

    public function maxPrice(int $min)
    {
        return $this->where('price', '<=', $min);
    }


}
