<?php

namespace App\Modules\V1\Platforms\Domain\Services;

use App\Modules\V1\Features\Domain\Enums\DynamicFeaturesValue;
use App\Modules\V1\Features\Domain\Models\DynamicFeatures;
use Illuminate\Support\Collection;

class PlatformPricingService
{
    public function calculate(Collection $features, array $dynamicPayload, int $days = 30): float|int
    {
        $featurePrice = $features->sum('price') * ($days / 30);

        $dynamicFeatures = DynamicFeatures::whereIn('name', DynamicFeaturesValue::values())->get();

        $dynamicFeaturePrice = $dynamicFeatures
            ->filter(fn ($dynamicFeature) => isset($dynamicPayload[$dynamicFeature->name]))
            ->sum(fn ($dynamicFeature) => $dynamicFeature->quantityPrice($dynamicPayload[$dynamicFeature->name]));

        return $featurePrice + $dynamicFeaturePrice;
    }
}
