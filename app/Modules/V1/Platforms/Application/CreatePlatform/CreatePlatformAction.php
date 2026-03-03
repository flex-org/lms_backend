<?php

namespace App\Modules\V1\Platforms\Application\CreatePlatform;

use App\Modules\V1\Dashboard\Admins\Models\Admin;
use App\Modules\V1\Features\Models\Feature;
use App\Modules\V1\Platforms\Domain\Services\PlatformPricingService;
use App\Modules\V1\Platforms\Enums\PLatformStatus;
use App\Modules\V1\Platforms\Models\Platform;
use App\Modules\V1\Themes\Models\Theme;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CreatePlatformAction
{
    public function __construct(private readonly PlatformPricingService $pricingService)
    {
    }

    public function execute(CreatePlatformData $data): array
    {
        return DB::transaction(function () use ($data) {
            $features = Feature::whereIn('id', $data->features)->get();
            $price = $this->pricingService->calculate($features, $data->toPricePayload());
            $defaultTheme = Theme::firstWhere('price', null);

            if (! $defaultTheme) {
                throw new RuntimeException('Default free theme is missing.');
            }

            $platform = Platform::create([
                'theme_id' => $defaultTheme->id,
                'domain' => $data->domain,
                'storage' => $data->storage,
                'capacity' => $data->capacity,
                'has_mobile_app' => $data->mobileApp,
                'started_at' => null,
                'renew_at' => null,
                'cost' => $price,
                'status' => PLatformStatus::FREE_TRIAL,
            ]);

            $platform->sellingSystems()->attach($data->sellingSystems);
            $this->assignPlatformFeatures($platform, $features);

            $admin = Admin::create([
                'domain' => $platform->domain,
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone,
                'password' => $data->password,
            ]);
            $admin->assignRole('owner');

            return [
                'platform_url' => $platform->domain . '.' . config('app.frontend_url'),
                'token' => $admin->createToken('owner')->plainTextToken,
            ];
        });
    }

    private function assignPlatformFeatures(Platform $platform, $features): void
    {
        $platform->features()->attach(
            $features->pluck('id')->mapWithKeys(fn ($id) => [
                $id => ['price' => $features->firstWhere('id', $id)['price']],
            ])
        );

        $permissions = $features->map(fn ($feature) => 'feature-' . $feature['id'])->toArray();

        $platform->givePermissionTo($permissions);
    }
}
