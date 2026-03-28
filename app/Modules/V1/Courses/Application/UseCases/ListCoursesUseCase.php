<?php

namespace App\Modules\V1\Courses\Application\UseCases;

use App\Modules\V1\Courses\Domain\Repositories\CourseRepositoryInterface;
use Illuminate\Support\Collection;

final readonly class ListCoursesUseCase
{
    public function __construct(
        private CourseRepositoryInterface $repository,
    ) {}

    public function execute(bool $active = true, array $filters = [])
    {
        return $this->repository->listByPlatform($active, $filters);
    }
}
