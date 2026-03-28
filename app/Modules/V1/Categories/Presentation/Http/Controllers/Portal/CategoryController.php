<?php

namespace App\Modules\V1\Catalog\Presentation\Http\Controllers\Portal;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\Catalog\Application\UseCases\CreateCategoryUseCase;
use App\Modules\V1\Catalog\Application\UseCases\DeleteCategoryUseCase;
use App\Modules\V1\Catalog\Application\UseCases\ListCategoriesUseCase;
use App\Modules\V1\Catalog\Application\UseCases\UpdateCategoryUseCase;
use App\Modules\V1\Catalog\Domain\Models\Category;
use App\Modules\V1\Catalog\Presentation\Http\Resources\CategoryResource;
use App\Modules\V1\Catalog\Presentation\Http\Resources\CourseResource;

class CategoryController extends Controller
{
    public function __construct(
        private readonly ListCategoriesUseCase $listCategories,
    ) {}
    public function index()
    {
        $categories = $this->listCategories->execute(false);

        return ApiResponse::success(CategoryResource::collection($categories)

        );
    }

    public function show(Category $category)
    {
        if (! $category->active) {
            abort(404);
        }

        $category->loadCount('courses')->load('media');

        return ApiResponse::success(new CategoryResource($category));
    }

    public function courses(Category $category)
    {
        if (! $category->active) {
            abort(404);
        }

        $courses = $category->courses()
            ->where('active', true)
            ->with('media')
            ->paginate();

        return ApiResponse::success(CourseResource::collection($courses)
            ->response()
            ->getData(true)
        );
    }
}
