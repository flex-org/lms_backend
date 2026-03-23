<?php

namespace App\Modules\V1\Billing\Application\UseCases;

use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Support\Facades\DB;

final readonly class DisableMobileUseCase
{
    public function __construct(
        private float $mobilePrice,
    ) {
    }

    public function execute(Platform $platform): void
    {
        if (! $platform->has_mobile_app) {
            return;
        }

        DB::transaction(function () use ($platform) {
            $platform->has_mobile_app = false;
            $platform->cost = max(0, (float) $platform->cost - $this->mobilePrice);
            $platform->save();
        });
    }
}

