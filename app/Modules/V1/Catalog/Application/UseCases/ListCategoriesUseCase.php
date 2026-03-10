<?php

namespace App\Modules\V1\Catalog\Application\UseCases;

use App\Modules\V1\Catalog\Domain\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Collection;

final readonly class ListCategoriesUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $repository,
    ) {}

    public function execute(int $platformId): Collection
    {
        return $this->repository->listByPlatform($platformId);
    }
}
