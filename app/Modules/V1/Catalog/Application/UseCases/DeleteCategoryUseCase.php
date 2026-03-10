<?php

namespace App\Modules\V1\Catalog\Application\UseCases;

use App\Modules\V1\Catalog\Domain\Models\Category;
use App\Modules\V1\Catalog\Domain\Repositories\CategoryRepositoryInterface;

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
