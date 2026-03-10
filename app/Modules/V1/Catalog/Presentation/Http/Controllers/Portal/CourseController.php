<?php

namespace App\Modules\V1\Catalog\Presentation\Http\Controllers\Portal;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\Catalog\Domain\Models\Course;
use App\Modules\V1\Catalog\Presentation\Http\Resources\CourseResource;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        private readonly TenantContextInterface $tenantContext,
    ) {}

    public function index(Request $request)
    {
        $courses = Course::where('platform_id', $this->tenantContext->getPlatformId())
            ->where('active', true)
            ->when(
                $request->integer('category_id'),
                fn ($q, $id) => $q->where('category_id', $id),
            )
            ->with('category')
            ->get();

        return ApiResponse::success(CourseResource::collection($courses));
    }

    public function show(Course $course)
    {
        if (! $course->active) {
            abort(404);
        }

        $course->load(['category', 'media']);

        return ApiResponse::success(new CourseResource($course));
    }
}
