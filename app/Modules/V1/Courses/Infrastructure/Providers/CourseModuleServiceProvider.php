<?php

namespace App\Modules\V1\Courses\Infrastructure\Providers;

use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\V1\Courses\Domain\Repositories\CourseRepositoryInterface;
use App\Modules\V1\Courses\Infrastructure\Persistence\Repositories\EloquentCourseRepository;

class CourseModuleServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(CourseRepositoryInterface::class, EloquentCourseRepository::class);
    }
}
