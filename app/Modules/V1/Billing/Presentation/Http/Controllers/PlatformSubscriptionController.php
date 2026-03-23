<?php

namespace App\Modules\V1\Billing\Presentation\Http\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Billing\Application\UseCases\ActivatePlatformFeatureUseCase;
use App\Modules\V1\Billing\Application\UseCases\DeactivatePlatformFeatureUseCase;
use App\Modules\V1\Billing\Application\UseCases\DisableMobileUseCase;
use App\Modules\V1\Billing\Application\UseCases\ReduceCapacityUseCase;
use App\Modules\V1\Billing\Application\UseCases\ReduceStorageUseCase;
use App\Modules\V1\Billing\Application\UseCases\RemoveFeatureUseCase;
use App\Modules\V1\Billing\Application\UseCases\RequestAddFeatureUseCase;
use App\Modules\V1\Billing\Application\UseCases\RequestCapacityUpgradeUseCase;
use App\Modules\V1\Billing\Application\UseCases\RequestEnableMobileUseCase;
use App\Modules\V1\Billing\Application\UseCases\RequestStorageUpgradeUseCase;
use App\Modules\V1\Billing\Presentation\Http\Requests\UpgradeCapacityRequest;
use App\Modules\V1\Billing\Presentation\Http\Requests\UpgradeFeatureRequest;
use App\Modules\V1\Billing\Presentation\Http\Requests\UpgradeStorageRequest;
use App\Modules\V1\Features\Domain\Models\Feature;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use Illuminate\Http\Request;

class PlatformSubscriptionController extends Controller
{
    public function __construct(
        private readonly TenantContextInterface $tenantContext,
        private readonly RequestAddFeatureUseCase $requestAddFeature,
        private readonly RequestStorageUpgradeUseCase $requestStorageUpgrade,
        private readonly RequestCapacityUpgradeUseCase $requestCapacityUpgrade,
        private readonly RequestEnableMobileUseCase $requestEnableMobile,
        private readonly RemoveFeatureUseCase $removeFeature,
        private readonly ReduceStorageUseCase $reduceStorage,
        private readonly ReduceCapacityUseCase $reduceCapacity,
        private readonly DisableMobileUseCase $disableMobile,
        private readonly DeactivatePlatformFeatureUseCase $deactivateFeature,
        private readonly ActivatePlatformFeatureUseCase $activateFeature,
    ) {
    }

    private function getPlatform(): Platform
    {
        return $this->tenantContext->getPlatform();
    }

    public function requestAddFeature(UpgradeFeatureRequest $request)
    {
        $platform = $this->getPlatform();
        $feature = Feature::findOrFail($request->integer('feature_id'));

        $pendingChange = $this->requestAddFeature->execute($platform, $feature);

        return ApiResponse::created(['pending_change_id' => $pendingChange->id]);
    }

    public function requestStorageUpgrade(UpgradeStorageRequest $request)
    {
        $platform = $this->getPlatform();
        $pendingChange = $this->requestStorageUpgrade->execute($platform, $request->integer('storage'));

        return ApiResponse::created(['pending_change_id' => $pendingChange->id]);
    }

    public function requestCapacityUpgrade(UpgradeCapacityRequest $request)
    {
        $platform = $this->getPlatform();
        $pendingChange = $this->requestCapacityUpgrade->execute($platform, $request->integer('capacity'));

        return ApiResponse::created(['pending_change_id' => $pendingChange->id]);
    }

    public function requestEnableMobile()
    {
        $platform = $this->getPlatform();
        $pendingChange = $this->requestEnableMobile->execute($platform);

        return ApiResponse::created(['pending_change_id' => $pendingChange->id]);
    }

    public function removeFeature(Feature $feature)
    {
        $platform = $this->getPlatform();
        $this->removeFeature->execute($platform, $feature);

        return ApiResponse::updated();
    }

    public function reduceStorage(Request $request)
    {
        $request->validate(['storage' => ['required', 'integer', 'min:1']]);
        $platform = $this->getPlatform();
        $this->reduceStorage->execute($platform, $request->integer('storage'));

        return ApiResponse::updated();
    }

    public function reduceCapacity(Request $request)
    {
        $request->validate(['capacity' => ['required', 'integer', 'min:1']]);
        $platform = $this->getPlatform();
        $this->reduceCapacity->execute($platform, $request->integer('capacity'));

        return ApiResponse::updated();
    }

    public function disableMobile()
    {
        $platform = $this->getPlatform();
        $this->disableMobile->execute($platform);

        return ApiResponse::updated();
    }

    public function deactivateFeature(int $featureId)
    {
        $platform = $this->getPlatform();
        $this->deactivateFeature->execute($platform, $featureId);

        return ApiResponse::updated();
    }

    public function activateFeature(int $featureId)
    {
        $platform = $this->getPlatform();
        $this->activateFeature->execute($platform, $featureId);

        return ApiResponse::updated();
    }
}

