<?php

namespace App\Modules\V1\Courses\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Courses\Domain\Models\Course;
use App\Modules\V1\Courses\Domain\Repositories\CourseRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentCourseRepository implements CourseRepositoryInterface
{
    public function listByPlatform(bool $active, array $filters)
    {
        return Course::when($active, fn ($q) => $q->whereActive(true))
        ->filter($filters)
        ->with('category')
        ->paginate(request('per_page', 15));
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
