<?php

namespace App\Modules\V1\Platforms\Application\CreatePlatform;

use App\Modules\V1\Platforms\Domain\Repositories\AdminRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Repositories\FeatureRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Repositories\PlatformRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Repositories\ThemeRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Services\PlatformPricingService;
use App\Modules\V1\Platforms\Enums\PLatformStatus;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CreatePlatformAction
{
    public function __construct(
        private readonly PlatformPricingService $pricingService,
        private readonly PlatformRepositoryInterface $platformRepository,
        private readonly FeatureRepositoryInterface $featureRepository,
        private readonly ThemeRepositoryInterface $themeRepository,
        private readonly AdminRepositoryInterface $adminRepository,
    ) {
    }

    public function execute(CreatePlatformData $data): array
    {
        return DB::transaction(function () use ($data) {
            $features = $this->featureRepository->getByIds($data->features);
            $price = $this->pricingService->calculate($features, $data->toPricePayload());
            $defaultTheme = $this->themeRepository->getDefaultFreeTheme();

            if (! $defaultTheme) {
                throw new RuntimeException('Default free theme is missing.');
            }

            $platform = $this->platformRepository->create([
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

            $this->platformRepository->attachSellingSystems($platform, $data->sellingSystems);
            $this->platformRepository->attachFeatures($platform, $features);
            $this->platformRepository->giveFeaturePermissions($platform, $features);

            $admin = $this->adminRepository->createOwner([
                'domain' => $platform->domain,
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone,
                'password' => $data->password,
            ]);

            return [
                'platform_url' => $platform->domain . '.' . config('app.frontend_url'),
                'token' => $admin->createToken('owner')->plainTextToken,
            ];
        });
    }
}
