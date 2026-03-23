<?php

namespace App\Modules\V1\Billing\Infrastructure\Providers;

use App\Modules\Shared\Infrastructure\Providers\AbstractModuleServiceProvider;
use App\Modules\V1\Billing\Domain\Repositories\InvoiceRepositoryInterface;
use App\Modules\V1\Billing\Domain\Repositories\PendingChangeRepositoryInterface;
use App\Modules\V1\Billing\Infrastructure\Persistence\Repositories\EloquentInvoiceRepository;
use App\Modules\V1\Billing\Infrastructure\Persistence\Repositories\EloquentPendingChangeRepository;

class BillingModuleServiceProvider extends AbstractModuleServiceProvider
{
    protected function registerBindings(): void
    {
        $this->app->bind(InvoiceRepositoryInterface::class, EloquentInvoiceRepository::class);
        $this->app->bind(PendingChangeRepositoryInterface::class, EloquentPendingChangeRepository::class);
    }
}

