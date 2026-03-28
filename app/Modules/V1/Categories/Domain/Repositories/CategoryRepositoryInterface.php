<?php

namespace App\Modules\V1\Categories\Domain\Repositories;

use App\Modules\V1\Categories\Domain\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    public function listByPlatform(array $filters, bool $active);

    public function findOrFail(int $id, bool $active, array $relations, array $relationsCount): Category;

    public function create(array $attributes): Category;

    public function update(Category $category, array $attributes): Category;

    public function delete(Category $category): void;

}
