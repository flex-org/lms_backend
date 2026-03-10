<?php

namespace App\Modules\V1\Catalog\Presentation\Http\Controllers\Admin;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Catalog\Application\DTOs\CreateCategoryData;
use App\Modules\V1\Catalog\Application\DTOs\UpdateCategoryData;
use App\Modules\V1\Catalog\Application\UseCases\CreateCategoryUseCase;
use App\Modules\V1\Catalog\Application\UseCases\DeleteCategoryUseCase;
use App\Modules\V1\Catalog\Application\UseCases\ListCategoriesUseCase;
use App\Modules\V1\Catalog\Application\UseCases\UpdateCategoryUseCase;
use App\Modules\V1\Catalog\Domain\Models\Category;
use App\Modules\V1\Catalog\Presentation\Http\Requests\StoreCategoryRequest;
use App\Modules\V1\Catalog\Presentation\Http\Requests\UpdateCategoryRequest;
use App\Modules\V1\Catalog\Presentation\Http\Resources\CategoryResource;
use App\Modules\V1\Catalog\Presentation\Http\Resources\CourseResource;

class CategoryController extends Controller
{
    public function __construct(
        private readonly ListCategoriesUseCase $listCategories,
        private readonly CreateCategoryUseCase $createCategory,
        private readonly UpdateCategoryUseCase $updateCategory,
        private readonly DeleteCategoryUseCase $deleteCategory,
        private readonly TenantContextInterface $tenantContext,
    ) {}

    public function index()
    {
        $categories = $this->listCategories->execute(
            $this->tenantContext->getPlatformId(),
        );

        return ApiResponse::success(CategoryResource::collection($categories));
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = CreateCategoryData::fromArray($request->validated());
        $category = $this->createCategory->execute($data);

        return ApiResponse::created(['category' => new CategoryResource($category)]);
    }

    public function show(Category $category)
    {
        $category->loadCount('courses')->load('media');

        return ApiResponse::success(new CategoryResource($category));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = UpdateCategoryData::fromArray($request->validated());
        $updated = $this->updateCategory->execute($category, $data);

        return ApiResponse::updated(['category' => new CategoryResource($updated)]);
    }

    public function courses(Category $category)
    {
        $courses = $category->courses()->with(['category', 'media'])->get();

        return ApiResponse::success(CourseResource::collection($courses));
    }

    public function destroy(Category $category)
    {
        $this->deleteCategory->execute($category);

        return ApiResponse::deleted();
    }
}
