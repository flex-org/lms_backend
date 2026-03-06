<?php

namespace App\Modules\V1\Platforms\Application\CreatePlatform;

use App\Modules\Shared\Domain\Contracts\PermissionRegistryInterface;
use App\Modules\V1\Platforms\Domain\Repositories\AdminRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Repositories\FeatureRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Repositories\PlatformRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Repositories\ThemeRepositoryInterface;
use App\Modules\V1\Platforms\Domain\Services\PlatformPricingService;
use App\Modules\V1\Platforms\Domain\Enums\PLatformStatus;
use Illuminate\Support\Facades\DB;

class CreatePlatformAction
{
    public function __construct(
        private readonly PlatformPricingService $pricingService,
        private readonly PlatformRepositoryInterface $platformRepository,
        private readonly FeatureRepositoryInterface $featureRepository,
        private readonly ThemeRepositoryInterface $themeRepository,
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly PermissionRegistryInterface $permissionRegistry,
    ) {
    }

    public function execute(CreatePlatformData $data): array
    {
        $this->guardAgainstDuplicateDomain($data);

        return DB::transaction(function () use ($data) {
            $features = $this->featureRepository->getByIds($data->features);
            $cost = $this->pricingService->calculate($features, $data->toPricePayload());
            $defaultTheme = $this->themeRepository->getDefaultFreeTheme();

            if (! $defaultTheme) {
                throw new \DomainException('Default free theme is missing.');
            }

            $platform = $this->platformRepository->create([
                'theme_id' => $defaultTheme->id,
                'domain' => $data->domain->value,
                'storage' => $data->storage->megabytes,
                'capacity' => $data->capacity->students,
                'has_mobile_app' => $data->mobileApp,
                'started_at' => null,
                'renew_at' => null,
                'cost' => $cost,
                'status' => PLatformStatus::FREE_TRIAL,
            ]);

            $this->platformRepository->attachSellingSystems($platform, $data->sellingSystems);
            $this->platformRepository->attachFeatures($platform, $features);
            $this->platformRepository->giveFeaturePermissions($platform, $features);

            $admin = $this->adminRepository->createOwner([
                'platform_id' => $platform->id,
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone,
                'password' => $data->password,
            ]);

            $featurePermissions = $features
                ->map(fn ($feature) => $this->permissionRegistry->featurePermission($feature['id']))
                ->toArray();

            $admin->givePermissionTo($featurePermissions);

            return [
                'platform_url' => $platform->domain . '.' . config('app.frontend_url'),
                'token' => $admin->createToken('owner', ['dashboard', $platform->domain])->plainTextToken,
            ];
        });
    }

    private function guardAgainstDuplicateDomain(CreatePlatformData $data): void
    {
        if ($this->platformRepository->domainExists($data->domain->value)) {
            throw new \DomainException('Domain already exists.');
        }
    }
}
