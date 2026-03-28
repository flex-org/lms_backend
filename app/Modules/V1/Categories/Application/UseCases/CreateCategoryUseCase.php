<?php

namespace App\Modules\V1\Catalog\Application\UseCases;

use App\Modules\V1\Catalog\Application\DTOs\CreateCategoryData;
use App\Modules\V1\Catalog\Domain\Models\Category;
use App\Modules\V1\Catalog\Domain\Repositories\CategoryRepositoryInterface;
use App\Traits\V1\HasTranslation;

final readonly class CreateCategoryUseCase
{
    use HasTranslation;

    public function __construct(
        private CategoryRepositoryInterface $repository,
    ) {}

    public function execute(CreateCategoryData $data): Category
    {
        $category = $this->repository->create($data->toAttributes());

        $this->fillTranslations($category, $data->translations);
        $category->save();

        if ($data->image) {
            $category->addMedia($data->image)->toMediaCollection('image');
        }

        return $category->load('media');
    }
}
