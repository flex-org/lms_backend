<?php

namespace App\Modules\V1\Platforms\Domain\Services;

use App\Modules\V1\Platforms\Domain\Repositories\DynamicFeatureRepositoryInterface;
use Illuminate\Support\Collection;

class PlatformPricingService
{
    public function __construct(
        private readonly DynamicFeatureRepositoryInterface $dynamicFeatureRepository,
    ) {
    }

    public function calculate(Collection $features, array $dynamicPayload, int $days = 30): float|int
    {
        $featurePrice = $features->sum('price') * ($days / 30);

        $dynamicFeatures = $this->dynamicFeatureRepository->getAllActive();
        $dynamicFeaturePrice = $dynamicFeatures
            ->filter(fn ($df) => isset($dynamicPayload[$df->name->value]))
            ->sum(fn ($df) => $df->quantityPrice($df->name, $dynamicPayload[$df->name->value]));

        return $featurePrice + $dynamicFeaturePrice;
    }
}
