<?php

namespace App\Modules\V1\Categories\Presentation\Http\Controllers\Admin;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\Categories\Application\DTOs\CreateCategoryData;
use App\Modules\V1\Categories\Application\DTOs\UpdateCategoryData;
use App\Modules\V1\Categories\Application\UseCases\CreateCategoryUseCase;
use App\Modules\V1\Categories\Application\UseCases\DeleteCategoryUseCase;
use App\Modules\V1\Categories\Application\UseCases\ListCategoriesUseCase;
use App\Modules\V1\Categories\Application\UseCases\ShowCategoryUseCase;
use App\Modules\V1\Categories\Application\UseCases\UpdateCategoryUseCase;
use App\Modules\V1\Categories\Domain\Models\Category;
use App\Modules\V1\Categories\Presentation\Http\Requests\StoreCategoryRequest;
use App\Modules\V1\Categories\Presentation\Http\Requests\UpdateCategoryRequest;
use App\Modules\V1\Categories\Presentation\Http\Resources\CategoryResource;
use App\Modules\V1\Courses\Application\UseCases\ListCoursesUseCase;
use App\Modules\V1\Courses\Presentation\Http\Resources\CourseResource;
use App\Traits\V1\Filterable;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use Filterable;
    public function __construct(
        private readonly ListCategoriesUseCase $listCategories,
        private readonly ShowCategoryUseCase $showCategory,
        private readonly CreateCategoryUseCase $createCategory,
        private readonly UpdateCategoryUseCase $updateCategory,
        private readonly DeleteCategoryUseCase $deleteCategory,
        private readonly ListCoursesUseCase $listCourses,

    ) {}

    public function index(Request $request)
    {
        $filters = $this->acceptedFilters(
            $request,
            ['name', 'active', 'min_price', 'max_price']
        );
        $categories = $this->listCategories->execute($filters, false);

        return ApiResponse::success(CategoryResource::collection($categories)
            ->response()
            ->getData(true)
        );
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = CreateCategoryData::fromArray($request->validated());
        $category = $this->createCategory->execute($data);

        return ApiResponse::created(['category' => new CategoryResource($category)]);
    }

    public function show(int $id)
    {
        $category = $this->showCategory->execute(
            $id,
            false,
            ['media']
        );
        return ApiResponse::success(new CategoryResource($category));
    }

    public function courses(Request $request, int $id)
    {
        $category = $this->showCategory->execute(
            id: $id,
            active: false,
            relations: ['media'],
            relationsCount: ['courses']
        );

        $filters = $this->acceptedFilters(
            $request,
            ['title', 'active', 'min_price', 'max_price']
        );

        collect($filters)->merge(['categories_id' => $id]);
        $courses = $this->listCourses->execute(false, $filters);

        return ApiResponse::success(
            CourseResource::collection($courses)
                ->additional([
                    'category' => new CategoryResource($category)
                ])
                ->response()
                ->getData(true)
        );
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = UpdateCategoryData::fromArray($request->validated());
        $updated = $this->updateCategory->execute($category, $data);

        return ApiResponse::updated(['category' => new CategoryResource($updated)]);
    }

    public function destroy(Category $category)
    {
        $this->deleteCategory->execute($category);

        return ApiResponse::deleted();
    }
}
