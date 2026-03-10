<?php

namespace App\Modules\V1\Catalog\Application\UseCases;

use App\Modules\V1\Catalog\Application\DTOs\UpdateCourseData;
use App\Modules\V1\Catalog\Domain\Models\Course;
use App\Modules\V1\Catalog\Domain\Repositories\CourseRepositoryInterface;
use App\Traits\V1\HasTranslation;

final readonly class UpdateCourseUseCase
{
    use HasTranslation;

    public function __construct(
        private CourseRepositoryInterface $repository,
    ) {}

    public function execute(Course $course, UpdateCourseData $data): Course
    {
        $attributes = $data->toAttributes();
        if (! empty($attributes)) {
            $this->repository->update($course, $attributes);
        }

        if ($data->translations) {
            $this->fillTranslations($course, $data->translations);
            $course->save();
        }

        if ($data->image) {
            $course->clearMediaCollection('image');
            $course->addMedia($data->image)->toMediaCollection('image');
        }

        return $course->fresh(['category', 'media']);
    }
}
