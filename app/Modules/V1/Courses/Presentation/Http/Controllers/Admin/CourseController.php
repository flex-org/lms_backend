<?php

namespace App\Modules\V1\Courses\Presentation\Http\Controllers\Admin;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Courses\Application\DTOs\CreateCourseData;
use App\Modules\V1\Courses\Application\DTOs\UpdateCourseData;
use App\Modules\V1\Courses\Application\UseCases\CreateCourseUseCase;
use App\Modules\V1\Courses\Application\UseCases\DeleteCourseUseCase;
use App\Modules\V1\Courses\Application\UseCases\ListCoursesUseCase;
use App\Modules\V1\Courses\Application\UseCases\UpdateCourseUseCase;
use App\Modules\V1\Courses\Domain\Models\Course;
use App\Modules\V1\Courses\Presentation\Http\Requests\StoreCourseRequest;
use App\Modules\V1\Courses\Presentation\Http\Requests\UpdateCourseRequest;
use App\Modules\V1\Courses\Presentation\Http\Resources\CourseResource;
use App\Traits\V1\Filterable;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use Filterable;
    public function __construct(
        private readonly ListCoursesUseCase $listCourses,
        private readonly CreateCourseUseCase $createCourse,
        private readonly UpdateCourseUseCase $updateCourse,
        private readonly DeleteCourseUseCase $deleteCourse,
    ) {}

    public function index(Request $request)
    {
        $filters = $this->acceptedFilters(
            $request,
            ['title', 'active', 'categories_id', 'min_price', 'max_price']
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

    public function store(StoreCourseRequest $request)
    {
        $data = CreateCourseData::fromArray($request->validated());
        $course = $this->createCourse->execute($data);

        return ApiResponse::created(['course' => new CourseResource($course)]);
    }

    public function show(Course $course)
    {
        $course->load(['category', 'media']);

        return ApiResponse::success(new CourseResource($course));
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $data = UpdateCourseData::fromArray($request->validated());
        $updated = $this->updateCourse->execute($course, $data);

        return ApiResponse::updated(['course' => new CourseResource($updated)]);
    }

    public function destroy(Course $course)
    {
        $this->deleteCourse->execute($course);

        return ApiResponse::deleted();
    }
}
