<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class CourseFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];


    public function categoriesId(string $value)
    {
        $categories_ids = explode(',', $value);
        return $this->whereIn('category_id', $categories_ids);
    }

    public function active(bool $value)
    {
        return $this->where('active', $value);
    }

    public function title(string $title)
    {
        return $this->whereTranslationLike('title', "%{$title}%");
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
