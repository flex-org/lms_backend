<?php

namespace App\Modules\V1\Catalog\Presentation\Http\Controllers\Portal;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\Catalog\Domain\Models\Category;
use App\Modules\V1\Catalog\Presentation\Http\Resources\CategoryResource;
use App\Modules\V1\Catalog\Presentation\Http\Resources\CourseResource;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;

class CategoryController extends Controller
{
    public function __construct(
        private readonly TenantContextInterface $tenantContext,
    ) {}

    public function index()
    {
        $categories = Category::where('platform_id', $this->tenantContext->getPlatformId())
            ->where('active', true)
            ->withCount('courses')
            ->get();

        return ApiResponse::success(CategoryResource::collection($categories));
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
            ->get();

        return ApiResponse::success(CourseResource::collection($courses));
    }
}
