<?php

namespace App\Modules\V1\Categories\Infrastructure\Providers;

use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\V1\Categories\Domain\Repositories\CategoryRepositoryInterface;
use App\Modules\V1\Categories\Infrastructure\Persistence\Repositories\EloquentCategoryRepository;

class CategoryModuleServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, EloquentCategoryRepository::class);
    }
}
