<?php
namespace App\Modules\V1\Platforms\Services;

use App\Modules\V1\Dashboard\Admins\Models\Admin;
use App\Modules\V1\Features\Enums\DynamicFeaturesValue;
use App\Modules\V1\Features\Models\DynamicFeatures;
use App\Modules\V1\Features\Models\Feature;
use App\Modules\V1\Platforms\Enums\PLatformStatus;
use App\Modules\V1\Platforms\Models\Platform;
use App\Modules\V1\Themes\Models\Theme;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PlatformService
{

    public function create(array $platformData): array
    {
        return DB::transaction(function () use ($platformData) {
            $features = Feature::whereIn('id', $platformData['features'])->get();
            $price = $this->subscriptionPriceCalculation($features, $platformData);
            $defaultTheme = Theme::firstWhere('price', null);

            if (! $defaultTheme) {
                throw new RuntimeException('Default free theme is missing.');
            }

            $platform = Platform::create([
                'theme_id' => $defaultTheme->id,
                'domain' => $platformData['domain'],
                'storage' => $platformData['storage'],
                'capacity' => $platformData['capacity'],
                'has_mobile_app' => $platformData['mobile_app'] ?? false,
                'started_at' => null,
                'renew_at' => null,
                'cost' => $price,
                'status' => PLatformStatus::FREE_TRIAL,
            ]);

            $platform->sellingSystems()->attach($platformData['selling_systems']);
            $this->assignPlatformFeatures($platform, $features);

            $admin = Admin::create([
                'domain' => $platform->domain,
                'name' => $platformData['name'],
                'email' => $platformData['email'],
                'phone' => $platformData['phone'],
                'password' => $platformData['password'],
            ]);
            $admin->assignRole('owner');

            return [
                'platform_url' => $platform->domain . '.' . config('app.frontend_url'),
                'token' => $admin->createToken('owner')->plainTextToken,
            ];
        });
    }

    public function subscriptionPriceCalculation($features, $platformData, $days = 30)
    {
        $featurePrice = $features->sum('price') * ($days/30);
        $dynamicFeatures = DynamicFeatures::whereIn(
            'name',
            DynamicFeaturesValue::values()
        )->get();

        $dynamicFeaturePrice = $dynamicFeatures
        ->filter(function ($dynamicFeature) use ($platformData) {
            return isset($platformData[$dynamicFeature->name]);
        })
        ->sum(function ($dynamicFeature) use ($platformData) {
            return $dynamicFeature->quantityPrice($platformData[$dynamicFeature->name]);
        });

        return $featurePrice + $dynamicFeaturePrice;
    }

    public function assignPlatformFeatures($platform, $features)
    {
        $platform->features()->attach(
            $features->pluck('id')
                ->mapWithKeys(fn ($id) => [
                    $id => ['price' => $features->firstWhere('id', $id)['price']]
                ])
        );

        $permissions = $features->map(
            fn($feature) => 'feature-' . $feature['id']
        )->toArray();

        $platform->givePermissionTo($permissions);
    }

}
