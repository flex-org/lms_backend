<?php

namespace App\Modules\V1\Billing\Presentation\Http\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Billing\Application\UseCases\ActivatePlatformFeatureUseCase;
use App\Modules\V1\Billing\Application\UseCases\DeactivatePlatformFeatureUseCase;
use App\Modules\V1\Billing\Application\UseCases\RemoveFeatureUseCase;
use App\Modules\V1\Billing\Application\UseCases\RequestAddFeatureUseCase;
use App\Modules\V1\Billing\Application\UseCases\RequestCapacityUpgradeUseCase;
use App\Modules\V1\Billing\Application\UseCases\RequestToggleMobileUseCase;
use App\Modules\V1\Billing\Application\UseCases\RequestStorageUpdateUseCase;
use App\Modules\V1\Billing\Presentation\Http\Requests\UpgradeCapacityRequest;
use App\Modules\V1\Billing\Presentation\Http\Requests\UpgradeStorageRequest;
use App\Modules\V1\Platforms\Domain\Models\Platform;

class PlatformSubscriptionController extends Controller
{
    public function __construct(
        private readonly TenantContextInterface           $tenantContext,
        private readonly RequestAddFeatureUseCase         $requestAddFeature,
        private readonly RequestStorageUpdateUseCase      $requestStorageUpgrade,
        private readonly RequestCapacityUpgradeUseCase    $requestCapacityUpgrade,
        private readonly RequestToggleMobileUseCase       $requestEnableMobile,
        private readonly RemoveFeatureUseCase             $removeFeature,
        private readonly DeactivatePlatformFeatureUseCase $deactivateFeature,
        private readonly ActivatePlatformFeatureUseCase   $activateFeature,
    ) {
    }

    private function getPlatform(): Platform
    {
        return $this->tenantContext->getPlatform();
    }

    public function requestAddFeature(string $featureKey)
    {
        $platform = $this->getPlatform();

        $pendingChange = $this->requestAddFeature->execute($platform, $featureKey);

        return ApiResponse::created(['pending_change_id' => $pendingChange->id]);
    }
    public function removeFeature(string $featureKey)
    {
        $platform = $this->getPlatform();
        $this->removeFeature->execute($platform, $featureKey);

        return ApiResponse::updated();
    }

    public function requestStorageUpdate(UpgradeStorageRequest $request)
    {
        $platform = $this->getPlatform();
        $pendingChange = $this->requestStorageUpgrade->execute($platform, $request->validated('storage'));
        return ApiResponse::updated(['pending_change_id' => $pendingChange?->id]);
    }

    public function requestCapacityUpdate(UpgradeCapacityRequest $request)
    {
        $platform = $this->getPlatform();
        $pendingChange = $this->requestCapacityUpgrade->execute($platform, $request->integer('capacity'));
        return ApiResponse::updated(['pending_change_id' => $pendingChange?->id]);
    }

    public function requestToggleMobile()
    {
        $platform = $this->getPlatform();
        $pendingChange = $this->requestEnableMobile->execute($platform);
        return ApiResponse::updated(['pending_change_id' => $pendingChange?->id]);
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

