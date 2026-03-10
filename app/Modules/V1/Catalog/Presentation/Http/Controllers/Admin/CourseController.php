<?php

namespace App\Modules\V1\Catalog\Presentation\Http\Controllers\Admin;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Catalog\Application\DTOs\CreateCourseData;
use App\Modules\V1\Catalog\Application\DTOs\UpdateCourseData;
use App\Modules\V1\Catalog\Application\UseCases\CreateCourseUseCase;
use App\Modules\V1\Catalog\Application\UseCases\DeleteCourseUseCase;
use App\Modules\V1\Catalog\Application\UseCases\ListCoursesUseCase;
use App\Modules\V1\Catalog\Application\UseCases\UpdateCourseUseCase;
use App\Modules\V1\Catalog\Domain\Models\Course;
use App\Modules\V1\Catalog\Presentation\Http\Requests\StoreCourseRequest;
use App\Modules\V1\Catalog\Presentation\Http\Requests\UpdateCourseRequest;
use App\Modules\V1\Catalog\Presentation\Http\Resources\CourseResource;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        private readonly ListCoursesUseCase $listCourses,
        private readonly CreateCourseUseCase $createCourse,
        private readonly UpdateCourseUseCase $updateCourse,
        private readonly DeleteCourseUseCase $deleteCourse,
        private readonly TenantContextInterface $tenantContext,
    ) {}

    public function index(Request $request)
    {
        $courses = $this->listCourses->execute(
            $this->tenantContext->getPlatformId(),
            $request->integer('category_id') ?: null,
        );

        return ApiResponse::success(CourseResource::collection($courses));
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
