<?php

namespace App\Traits\V1;

use Illuminate\Database\Eloquent\Model;

trait Filterable
{
    public function acceptedFilters($request, $keys)
    {
        $filter = [];
        foreach ($keys as $key) {
            if($request->filled($key))
                $filter[$key] = $request->query($key);
        }
        return $filter;
    }

}
