<?php

namespace App\Modules\V1\Billing\Infrastructure\Persistence\Repositories;

use App\Modules\V1\Billing\Domain\Models\PlatformPendingChange;
use App\Modules\V1\Billing\Domain\Repositories\PendingChangeRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Collection;

class EloquentPendingChangeRepository implements PendingChangeRepositoryInterface
{
    public function create(array $attributes): PlatformPendingChange
    {
        return PlatformPendingChange::create($attributes);
    }

    public function find(int $id): ?PlatformPendingChange
    {
        return PlatformPendingChange::find($id);
    }

    public function forPlatform(Platform $platform): Collection
    {
        return $platform->pendingChanges()->latest()->get();
    }
}

