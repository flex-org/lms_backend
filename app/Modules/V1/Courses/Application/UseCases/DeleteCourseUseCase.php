<?php

namespace App\Modules\V1\Courses\Application\UseCases;

use App\Modules\V1\Courses\Domain\Models\Course;
use App\Modules\V1\Courses\Domain\Repositories\CourseRepositoryInterface;

final readonly class DeleteCourseUseCase
{
    public function __construct(
        private CourseRepositoryInterface $repository,
    ) {}

    public function execute(Course $course): void
    {
        $this->repository->delete($course);
    }
}
