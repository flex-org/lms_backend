<?php

namespace App\Traits\V1;

use App\Models\V1\Scopes\DomainScope;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;

trait BelongsToDomain
{
    protected static function bootBelongsToDomain(): void
    {
        static::addGlobalScope(new DomainScope);

        static::creating(function ($model) {
            $tenantContext = app(TenantContextInterface::class);

            if ($tenantContext->isResolved() && empty($model->domain)) {
                $model->domain = $tenantContext->getDomain();
            }
        });
    }

    public function getDomain(): string
    {
        return $this->domain;
    }
}
