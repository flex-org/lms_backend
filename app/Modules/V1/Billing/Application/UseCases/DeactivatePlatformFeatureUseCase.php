<?php

namespace App\Modules\V1\Billing\Application\UseCases;

use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Facades\DB;

final readonly class DeactivatePlatformFeatureUseCase
{
    public function execute(Platform $platform, int $featureId): void
    {
        DB::transaction(function () use ($platform, $featureId) {
            $platform->features()
                ->updateExistingPivot($featureId, ['is_active' => false]);
        });
    }
}

