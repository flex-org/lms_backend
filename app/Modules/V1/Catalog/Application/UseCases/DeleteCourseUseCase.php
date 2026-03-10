<?php

namespace App\Modules\V1\Catalog\Application\UseCases;

use App\Modules\V1\Catalog\Domain\Models\Course;
use App\Modules\V1\Catalog\Domain\Repositories\CourseRepositoryInterface;

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
