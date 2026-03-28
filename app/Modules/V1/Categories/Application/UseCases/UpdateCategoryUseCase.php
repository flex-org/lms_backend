<?php

namespace App\Modules\V1\Catalog\Application\UseCases;

use App\Modules\V1\Catalog\Application\DTOs\UpdateCategoryData;
use App\Modules\V1\Catalog\Domain\Models\Category;
use App\Modules\V1\Catalog\Domain\Repositories\CategoryRepositoryInterface;
use App\Traits\V1\HasTranslation;

final readonly class UpdateCategoryUseCase
{
    use HasTranslation;

    public function __construct(
        private CategoryRepositoryInterface $repository,
    ) {}

    public function execute(Category $category, UpdateCategoryData $data): Category
    {
        $attributes = $data->toAttributes();
        if (! empty($attributes)) {
            $this->repository->update($category, $attributes);
        }

        if ($data->translations) {
            $this->fillTranslations($category, $data->translations);
            $category->save();
        }

        if ($data->image) {
            $category->clearMediaCollection('image');
            $category->addMedia($data->image)->toMediaCollection('image');
        }

        return $category->fresh(['media']);
    }
}
