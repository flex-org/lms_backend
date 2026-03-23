<?php

namespace App\Modules\V1\Billing\Domain\Repositories;

use App\Modules\V1\Billing\Domain\Models\PlatformPendingChange;
use App\Modules\V1\Platforms\Domain\Models\Platform;

interface PendingChangeRepositoryInterface
{
    public function create(array $attributes): PlatformPendingChange;

    public function find(int $id): ?PlatformPendingChange;

    public function forPlatform(Platform $platform);
}

