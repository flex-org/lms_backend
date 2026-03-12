<?php

namespace App\Modules\V1\Platforms\Presentation\Http\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Features\Domain\Models\Feature;
use App\Modules\V1\Platforms\Application\CreatePlatform\CreatePlatformAction;
use App\Modules\V1\Platforms\Application\CreatePlatform\CreatePlatformData;
use App\Modules\V1\Platforms\Domain\Models\Platform;
use App\Modules\V1\Platforms\Presentation\Http\Requests\PlatformCreateRequest;
use App\Modules\V1\Platforms\Presentation\Http\Resources\PlatformFeatureResource;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function __construct(
        private readonly CreatePlatformAction $createPlatformAction,
        private readonly TenantContextInterface $tenantContext,
    ) {}

    public function store(PlatformCreateRequest $request)
    {
        $platform = $this->createPlatformAction->execute(CreatePlatformData::fromArray($request->validated()));

        return ApiResponse::created(data: $platform);
    }

    public function features()
    {
        $platform = $this->tenantContext->getPlatform();

        $platformFeatureIds = $platform->features()->pluck('features.id');

        $allFeatures = Feature::where('active', true)
            ->with('translations')
            ->get()
            ->each(fn ($f) => $f->included = $platformFeatureIds->contains($f->id));

        $sellingSystems = $platform->sellingSystems
            ->map(function ($sellingSystem) {
                $enum = $sellingSystem->system;

                return [
                    'key'   => $enum->value,
                    'value' => $enum->label(),
                ];
            })
            ->values();

        $activeTheme = $platform->theme
            ? [
                'id'     => $platform->theme->id,
                'name'   => $platform->theme->name,
                'color'  => $platform->theme->color,
                'colors' => $platform->theme->colors,
            ]
            : null;

        return ApiResponse::success([
            'features'        => PlatformFeatureResource::collection($allFeatures),
            'selling_systems' => $sellingSystems,
            'theme'           => $activeTheme,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->query('q', '');

        if (strlen($query) < 2) {
            return ApiResponse::success([]);
        }

        $platforms = Platform::where('domain', 'LIKE', "%{$query}%")
            ->select('id', 'domain')
            ->limit(20)
            ->get();

        return ApiResponse::success($platforms);
    }

    public function show(string $id)
    {
    }

    public function destroy(string $id)
    {
    }
}
