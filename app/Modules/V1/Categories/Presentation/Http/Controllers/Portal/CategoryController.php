<?php

namespace App\Modules\V1\Categories\Presentation\Http\Controllers\Portal;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\Categories\Application\UseCases\ListCategoriesUseCase;
use App\Modules\V1\Categories\Application\UseCases\ShowCategoryUseCase;
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
        private readonly ListCoursesUseCase $listCourses,
        private readonly ShowCategoryUseCase $showCategory,
    ) {}
    public function index(Request $request)
    {
        $filters = $this->acceptedFilters(
            $request,
            ['name', 'min_price', 'max_price']
        );
        $categories = $this->listCategories->execute($filters, false);

        return ApiResponse::success(CategoryResource::collection($categories)
            ->response()
            ->getData(true)
        );
    }

    public function courses(Request $request, int $id)
    {
        $category = $this->showCategory->execute(
            id: $id,
            active: true,
            relations: ['media'],
            relationsCount: ['courses']
        );

        $filters = $this->acceptedFilters(
            $request,
            ['title', 'min_price', 'max_price']
        );

        collect($filters)->merge(['categories_id' => $id]);

        $courses = $this->listCourses->execute(true);

        return ApiResponse::success(
            CourseResource::collection($courses)
                ->additional([
                    'category' => new CategoryResource($category)
                ])
                ->response()
                ->getData(true)
        );
    }
}
