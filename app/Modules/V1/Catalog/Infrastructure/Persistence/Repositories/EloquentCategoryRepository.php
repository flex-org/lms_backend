<?php

namespace App\Modules\V1\Catalog\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Catalog\Domain\Models\Category;
use App\Modules\V1\Catalog\Domain\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function listByPlatform(int $platformId): Collection
    {
        return Category::where('platform_id', $platformId)
            ->withCount('courses')
            ->get();
    }

    public function findOrFail(int $id): Category
    {
        return Category::with('media')->findOrFail($id);
    }

    public function create(array $attributes): Category
    {
        return Category::create($attributes);
    }

    public function update(Category $category, array $attributes): Category
    {
        $category->update($attributes);

        return $category->fresh();
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
