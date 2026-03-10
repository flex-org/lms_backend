<?php

namespace App\Modules\V1\Catalog\Application\UseCases;

use App\Modules\V1\Catalog\Application\DTOs\CreateCourseData;
use App\Modules\V1\Catalog\Domain\Models\Course;
use App\Modules\V1\Catalog\Domain\Repositories\CourseRepositoryInterface;
use App\Traits\V1\HasTranslation;

final readonly class CreateCourseUseCase
{
    use HasTranslation;

    public function __construct(
        private CourseRepositoryInterface $repository,
    ) {}

    public function execute(CreateCourseData $data): Course
    {
        $course = $this->repository->create($data->toAttributes());

        $this->fillTranslations($course, $data->translations);
        $course->save();

        if ($data->image) {
            $course->addMedia($data->image)->toMediaCollection('image');
        }

        return $course->load(['category', 'media']);
    }
}
