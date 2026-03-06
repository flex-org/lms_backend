<?php

namespace App\Models\V1\Scopes;

use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PlatformScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $id = app(TenantContextInterface::class)->getPlatformId();

        if ($id !== null) {
            $builder->where('platform_id', $id);
        }
    }
}
