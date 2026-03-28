<?php

namespace App\Modules\V1\Catalog\Infrastructure\Providers;

use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\V1\Catalog\Domain\Repositories\CategoryRepositoryInterface;
use App\Modules\V1\Catalog\Domain\Repositories\CourseRepositoryInterface;
use App\Modules\V1\Catalog\Infrastructure\Persistence\Repositories\EloquentCategoryRepository;
use App\Modules\V1\Catalog\Infrastructure\Persistence\Repositories\EloquentCourseRepository;

class CatalogModuleServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, EloquentCategoryRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, EloquentCourseRepository::class);
    }
}
