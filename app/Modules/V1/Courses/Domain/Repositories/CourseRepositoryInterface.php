<?php

namespace App\Modules\V1\Courses\Domain\Repositories;

use App\Modules\V1\Courses\Domain\Models\Course;
use Illuminate\Support\Collection;

interface CourseRepositoryInterface
{
    public function listByPlatform(bool $active, array $filters);

    public function findOrFail(int $id): Course;

    public function create(array $attributes): Course;

    public function update(Course $course, array $attributes): Course;

    public function delete(Course $course): void;
}
