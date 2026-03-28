<?php

namespace App\Modules\V1\Categories\Application\UseCases;

use App\Modules\V1\Categories\Domain\Repositories\CategoryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class ShowCategoryUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $repository,
    ) {}

    public function execute(int $id, bool $active = true, array $relations = [], array $relationsCount = [])
    {
        return $this->repository->findOrFail($id, $active, $relations, $relationsCount);
    }
}
