<?php

namespace App\Modules\V1\Catalog\Domain\Repositories;

use App\Modules\V1\Catalog\Domain\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    public function listByPlatform(int $perPage,array $filters, bool $active);

    public function findOrFail(int $id): Category;

    public function create(array $attributes): Category;

    public function update(Category $category, array $attributes): Category;

    public function delete(Category $category): void;
}
