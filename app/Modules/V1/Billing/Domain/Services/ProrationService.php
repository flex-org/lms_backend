<?php

namespace App\Modules\V1\Billing\Domain\Services;

use App\Modules\V1\Platforms\Domain\Models\Platform;
use App\Modules\V1\Platforms\Domain\Services\PlatformPricingService;
use Carbon\CarbonInterface;

class ProrationService
{
    public function __construct(
        private readonly PlatformPricingService $pricingService,
    ) {
    }

    public function daysRemaining(Platform $platform, ?CarbonInterface $now = null): int
    {
        $now = $now ?? now();

        if (! $platform->renew_at) {
            return 30;
        }

        return max(0, $now->diffInDays($platform->renew_at));
    }

    public function featureProration(float $featurePrice, int $daysRemaining): float
    {
        if ($daysRemaining <= 0) {
            return 0.0;
        }

        return $featurePrice * ($daysRemaining / 30);
    }

    public function dynamicProration(float $oldPrice, float $newPrice, int $daysRemaining): float
    {
        if ($daysRemaining <= 0) {
            return 0.0;
        }

        $diff = $newPrice - $oldPrice;

        if ($diff <= 0) {
            return 0.0;
        }

        return $diff * ($daysRemaining / 30);
    }
}

