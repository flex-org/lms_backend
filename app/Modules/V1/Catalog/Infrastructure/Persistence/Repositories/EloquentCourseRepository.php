<?php

namespace App\Modules\V1\Catalog\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Catalog\Domain\Models\Course;
use App\Modules\V1\Catalog\Domain\Repositories\CourseRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentCourseRepository implements CourseRepositoryInterface
{
    public function listByPlatform(int $platformId, ?int $categoryId = null): Collection
    {
        return Course::where('platform_id', $platformId)
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->with('category')
            ->get();
    }

    public function findOrFail(int $id): Course
    {
        return Course::with(['category', 'media'])->findOrFail($id);
    }

    public function create(array $attributes): Course
    {
        return Course::create($attributes);
    }

    public function update(Course $course, array $attributes): Course
    {
        $course->update($attributes);

        return $course->fresh(['category']);
    }

    public function delete(Course $course): void
    {
        $course->delete();
    }
}
