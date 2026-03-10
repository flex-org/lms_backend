<?php

namespace App\Modules\V1\Catalog\Application\UseCases;

use App\Modules\V1\Catalog\Domain\Repositories\CourseRepositoryInterface;
use Illuminate\Support\Collection;

final readonly class ListCoursesUseCase
{
    public function __construct(
        private CourseRepositoryInterface $repository,
    ) {}

    public function execute(int $platformId, ?int $categoryId = null): Collection
    {
        return $this->repository->listByPlatform($platformId, $categoryId);
    }
}
