<?php

namespace App\Modules\V1\Categories\Application\UseCases;

use App\Modules\V1\Categories\Domain\Models\Category;
use App\Modules\V1\Categories\Domain\Repositories\CategoryRepositoryInterface;

final readonly class DeleteCategoryUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $repository,
    ) {}

    public function execute(Category $category): void
    {
        $this->repository->delete($category);
    }
}
