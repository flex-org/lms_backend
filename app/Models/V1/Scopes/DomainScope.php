<?php

namespace App\Models\V1\Scopes;

use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DomainScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $tenantContext = app(TenantContextInterface::class);

        if ($tenantContext->isResolved()) {
            $builder->where('domain', $tenantContext->getDomain());
        }
    }
}
