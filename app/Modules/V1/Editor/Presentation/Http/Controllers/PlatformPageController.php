<?php

namespace App\Modules\V1\Editor\Presentation\Http\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Editor\Application\UseCases\ListPlatformPagesUseCase;
use App\Modules\V1\Editor\Application\UseCases\UpdatePlatformPageUseCase;
use App\Modules\V1\Editor\Domain\Models\PlatformPage;
use App\Modules\V1\Editor\Domain\Repositories\PlatformPageRepositoryInterface;
use App\Modules\V1\Editor\Presentation\Http\Requests\UpdatePlatformPageRequest;
use App\Modules\V1\Editor\Presentation\Http\Resources\PlatformPageResource;

class PlatformPageController extends Controller
{
    public function __construct(
        private readonly ListPlatformPagesUseCase $listPages,
        private readonly UpdatePlatformPageUseCase $updatePage,
        private readonly PlatformPageRepositoryInterface $pageRepository,
        private readonly TenantContextInterface $tenantContext,
    ) {}

    public function index()
    {
        $platform = $this->tenantContext->getPlatform();

        if (! $platform) {
            abort(404, 'Platform not found.');
        }

        $pages = $this->listPages->execute($platform->id);

        return ApiResponse::success(PlatformPageResource::collection($pages));
    }

    public function show(PlatformPage $platformPage)
    {
        $this->ensureBelongsToPlatform($platformPage);

        $platformPage = $this->pageRepository->findOrFail($platformPage->id);

        return ApiResponse::success(new PlatformPageResource($platformPage));
    }

    public function update(UpdatePlatformPageRequest $request, PlatformPage $platformPage)
    {
        $this->ensureBelongsToPlatform($platformPage);

        $updated = $this->updatePage->execute($platformPage, $request->validated());

        return ApiResponse::updated(['page' => new PlatformPageResource($updated)]);
    }

    private function ensureBelongsToPlatform(PlatformPage $platformPage): void
    {
        $platform = $this->tenantContext->getPlatform();

        if (! $platform || $platformPage->platform_id !== $platform->id) {
            abort(403, 'Page does not belong to this platform.');
        }
    }
}
