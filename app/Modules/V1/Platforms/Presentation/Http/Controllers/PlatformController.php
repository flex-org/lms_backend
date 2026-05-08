<?php

namespace App\Modules\V1\Platforms\Presentation\Http\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Platforms\Application\DTOs\CreatePlatformData;
use App\Modules\V1\Platforms\Application\UseCases\CreatePlatformUseCase;
use App\Modules\V1\Platforms\Application\UseCases\GetPlatformOverViewUseCase;
use App\Modules\V1\Platforms\Application\UseCases\RemoveFeatureUseCase;
use App\Modules\V1\Platforms\Application\UseCases\RequestAddFeatureUseCase;
use App\Modules\V1\Platforms\Application\UseCases\RequestCapacityUpgradeUseCase;
use App\Modules\V1\Platforms\Application\UseCases\RequestStorageUpdateUseCase;
use App\Modules\V1\Platforms\Application\UseCases\RequestToggleMobileUseCase;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use App\Modules\V1\Platforms\Presentation\Http\Requests\PlatformCreateRequest;
use App\Modules\V1\Platforms\Presentation\Http\Requests\UpgradeCapacityRequest;
use App\Modules\V1\Platforms\Presentation\Http\Requests\UpgradeStorageRequest;
use App\Modules\V1\Platforms\Presentation\Http\Resources\PlatformFeatureResource;
use App\Modules\V1\Platforms\Presentation\Http\Resources\PlatformSellingSystemResource;
use App\Modules\V1\Themes\Presentation\Http\Resources\ThemeResource;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function __construct(
        private readonly CreatePlatformUseCase $createPlatformUseCase,
        private readonly GetPlatformOverViewUseCase $getPlatformOverViewUseCase,
        private readonly TenantContextInterface $tenantContext,
        private readonly RequestAddFeatureUseCase $requestAddFeature,
        private readonly RequestStorageUpdateUseCase $requestStorageUpgrade,
        private readonly RequestCapacityUpgradeUseCase $requestCapacityUpgrade,
        private readonly RequestToggleMobileUseCase $requestEnableMobile,
        private readonly RemoveFeatureUseCase $removeFeature,
    ) {}

    public function store(PlatformCreateRequest $request)
    {
        $platform = $this->createPlatformUseCase->execute(
            CreatePlatformData::fromArray($request->validated())
        );
        return ApiResponse::created(data: $platform);
    }

    public function features()
    {
        $platform = $this->tenantContext->getPlatform();

        $platformOverView = $this->getPlatformOverViewUseCase->execute($platform);

        return ApiResponse::success([
            'features'        => PlatformFeatureResource::collection($platformOverView['features']),
            'selling_systems' => PlatformSellingSystemResource::collection($platformOverView['selling_systems']),
            'theme'           => new ThemeResource($platformOverView['theme']),
            'template'        => $platformOverView['template']
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->query('q', '');

        if (strlen($query) < 2) {
            return ApiResponse::success([]);
        }

        $platforms = Platform::where('domain', 'LIKE', "%{$query}%")
            ->orWhere('name','LIKE', "%{$query}%")
            ->select('id', 'domain', 'name')
            ->where('has_mobile_app', true)
            ->limit(20)
            ->get();

        return ApiResponse::success($platforms);
    }

    private function getPlatform(): Platform
    {
        return $this->tenantContext->getPlatform();
    }

    public function requestAddFeature(string $featureKey)
    {
        $platform = $this->getPlatform();

        $invoice = $this->requestAddFeature->execute($platform, $featureKey);

        return ApiResponse::created(['invoice_id' => $invoice->id]);
    }

    public function requestStorageUpdate(UpgradeStorageRequest $request)
    {
        $platform = $this->getPlatform();
        $invoice = $this->requestStorageUpgrade->execute($platform, $request->validated('storage'));
        return ApiResponse::updated(['invoice_id' => $invoice?->id]);
    }

    public function requestCapacityUpdate(UpgradeCapacityRequest $request)
    {
        $platform = $this->getPlatform();
        $invoice = $this->requestCapacityUpgrade->execute($platform, $request->integer('capacity'));
        return ApiResponse::updated(['invoice_id' => $invoice?->id]);
    }

    public function requestToggleMobile()
    {
        $platform = $this->getPlatform();
        $invoice = $this->requestEnableMobile->execute($platform);
        return ApiResponse::updated(['invoice_id' => $invoice?->id]);
    }

    public function removeFeature(string $featureKey)
    {
        $platform = $this->getPlatform();
        $this->removeFeature->execute($platform, $featureKey);

        return ApiResponse::updated();
    }

    public function toggleActivateFeature(int $featureId, string $status)
    {
        $this->getPlatform()
            ->features()
            ->updateExistingPivot($featureId, ['is_active' => ($status = 'activate') ? true : false]);

        return ApiResponse::updated();
    }

}
