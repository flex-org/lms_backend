<?php

namespace App\Modules\V1\Billing\Application\UseCases;

use App\Modules\V1\Features\Domain\Models\Feature;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Facades\DB;

final readonly class RemoveFeatureUseCase
{
    public function execute(Platform $platform, Feature $feature): void
    {
        DB::transaction(function () use ($platform, $feature) {
            $platform->features()->detach($feature->id);

            if ($feature->price) {
                $platform->cost = max(0, (float) $platform->cost - (float) $feature->price);
                $platform->save();
            }
        });
    }
}

