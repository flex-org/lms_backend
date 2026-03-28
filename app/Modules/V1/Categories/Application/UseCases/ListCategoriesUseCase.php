<?php

namespace App\Modules\V1\Catalog\Application\UseCases;

use App\Modules\V1\Catalog\Domain\Repositories\CategoryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class ListCategoriesUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $repository,
    ) {}

    public function execute($perPage = 15, $filters = [], bool $active = true)
    {
        return $this->repository->listByPlatform($perPage, $filters, $active);
    }
}
