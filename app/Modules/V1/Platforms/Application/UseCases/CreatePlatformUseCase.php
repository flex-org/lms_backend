<?php

namespace App\Modules\V1\Platforms\Application\UseCases;

use App\Modules\V1\Admins\Domain\Repositories\AdminRepositoryInterface;
use App\Modules\V1\Editor\Application\UseCases\InitializePlatformBuilderUseCase;
use App\Modules\V1\Features\Domain\Repositories\FeatureRepositoryInterface;
use App\Modules\V1\Platforms\Application\DTOs\CreatePlatformData;
use App\Modules\V1\Platforms\Domain\Enums\PLatformStatus;
use App\Modules\V1\Platforms\Domain\Repositories\PlatformRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Services\PlatformPricingService;
use App\Modules\V1\Themes\Domain\Repositories\ThemeRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreatePlatformUseCase
{
    public function __construct(
        private readonly PlatformPricingService $pricingService,
        private readonly PlatformRepositoryInterface $platformRepository,
        private readonly FeatureRepositoryInterface $featureRepository,
        private readonly ThemeRepositoryInterface $themeRepository,
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly InitializePlatformBuilderUseCase $initializePlatformBuilder,
    ) {
    }

    public function execute(CreatePlatformData $data): array
    {
        $features = $this->featureRepository->listByKeys($data->features);
        $cost = $this->pricingService->calculate($features, $data->toPricePayload());
        $defaultTheme = $this->themeRepository->getDefaultFreeTheme();

        return DB::transaction(function () use ($data, $features, $cost, $defaultTheme) {

            $platform = $this->platformRepository->create(
                $this->platformFormat($data, $defaultTheme, $cost)
            );

            $this->platformRepository->attachSellingSystems($platform);
            $this->platformRepository->attachFeatures($platform, $features);
            $this->platformRepository->giveFeaturePermissions($platform, $features);

            $admin = $this->adminRepository->create(
                $this->ownerFormat($data, $platform)
            );

            $this->initializePlatformBuilder->execute($platform);

            return [
                'platform_url' => $platform->domain . '.' . config('app.frontend_url'),
                'token' => $admin->createToken('owner', ['dashboard', $platform->domain])->plainTextToken,
            ];
        });
    }
    private function platformFormat($data, $defaultTheme, $cost)
    {
        return [
            'theme_id' => $defaultTheme->id,
            'domain' => $data->domain,
            'storage' => $data->storage,
            'capacity' => $data->capacity,
            'has_mobile_app' => $data->mobileApp,
            'started_at' => null,
            'renew_at' => null,
            'cost' => $cost,
            'status' => PLatformStatus::FREE_TRIAL,
        ];
    }

    private function ownerFormat($data, $platform)
    {
        return [
            'platform_id' => $platform->id,
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
            'password' => Hash::make($data->password),
            'role' => 'owner',
        ];
    }
}
