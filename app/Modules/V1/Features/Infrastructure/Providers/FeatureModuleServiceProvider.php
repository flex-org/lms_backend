<?php

namespace App\Modules\V1\Features\Infrastructure\Providers;

use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\V1\Features\Domain\Repositories\FeatureRepositoryInterface;
use App\Modules\V1\Features\Infrastructure\Persistence\Repositories\EloquentFeatureRepository;

class FeatureModuleServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(
            FeatureRepositoryInterface::class,
            EloquentFeatureRepository::class);
    }
}
