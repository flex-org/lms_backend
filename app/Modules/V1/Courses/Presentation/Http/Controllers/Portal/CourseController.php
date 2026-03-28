<?php

namespace App\Modules\V1\Courses\Presentation\Http\Controllers\Portal;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\Courses\Application\UseCases\ListCoursesUseCase;
use App\Modules\V1\Courses\Domain\Models\Course;
use App\Modules\V1\Courses\Presentation\Http\Resources\CourseResource;
use App\Traits\V1\Filterable;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use Filterable;
    public function __construct(
        private readonly ListCoursesUseCase $listCourses,
    ) {}

    public function index(Request $request)
    {
        $filters = $this->acceptedFilters(
            $request,
            ['title', 'categories_id', 'min_price', 'max_price']
        );

        $courses = $this->listCourses->execute(
            active: false,
            filters: $filters
        );

        return ApiResponse::success(CourseResource::collection($courses)
            ->response()
            ->getData(true)
        );
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
