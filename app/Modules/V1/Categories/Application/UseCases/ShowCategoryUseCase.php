<?php

namespace App\Modules\V1\Categories\Application\UseCases;

use App\Modules\V1\Categories\Domain\Repositories\CategoryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class ListCategoriesUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $repository,
    ) {}

    public function execute($filters = [], bool $active = true)
    {
        return $this->repository->listByPlatform($filters, $active);
    }
}
