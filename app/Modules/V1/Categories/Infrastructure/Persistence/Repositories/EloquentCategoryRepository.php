<?php

namespace App\Modules\V1\Categories\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Categories\Domain\Models\Category;
use App\Modules\V1\Categories\Domain\Repositories\CategoryRepositoryInterface;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function listByPlatform(array $filters, bool $active)
    {
        return Category::withCount('courses')
            ->filter($filters)
            ->when($active,
                fn ($q) => $q->whereActive(true)
            )
            ->paginate(request('per_page', 15));
    }

    public function findOrFail(int $id , bool $active, array $relations, array $relationsCount): Category
    {
        return Category::when(
            $relations,
            fn($q) => $q->with($relations)
        )->when(
            $relationsCount,
            fn($q) => $q->withCount($relationsCount)
        )->when(
            $active,
            fn ($q) => $q->whereActive(true)
        )->findOrFail($id);
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
