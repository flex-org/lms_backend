<?php

namespace App\Modules\V1\Editor\Presentation\Http\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Editor\Application\DTOs\CreatePlatformPageData;
use App\Modules\V1\Editor\Application\UseCases\CreatePlatformPageUseCase;
use App\Modules\V1\Editor\Application\UseCases\DeletePlatformPageUseCase;
use App\Modules\V1\Editor\Application\UseCases\ListPlatformPagesUseCase;
use App\Modules\V1\Editor\Application\UseCases\UpdatePlatformPageUseCase;
use App\Modules\V1\Editor\Domain\Models\PlatformPage;
use App\Modules\V1\Editor\Presentation\Http\Requests\StorePlatformPageRequest;
use App\Modules\V1\Editor\Presentation\Http\Requests\UpdatePlatformPageRequest;

class PlatformPageController extends Controller
{
    public function __construct(
        private readonly ListPlatformPagesUseCase $listPages,
        private readonly CreatePlatformPageUseCase $createPage,
        private readonly UpdatePlatformPageUseCase $updatePage,
        private readonly DeletePlatformPageUseCase $deletePage,
        private readonly TenantContextInterface $tenantContext,
    ) {
    }

    public function index()
    {
        $platform = $this->tenantContext->getPlatform();

        if (! $platform) {
            abort(404, 'Platform not found.');
        }

        $pages = $this->listPages->execute($platform->id);

        return ApiResponse::success($pages);
    }

    public function store(StorePlatformPageRequest $request)
    {
        $platform = $this->tenantContext->getPlatform();

        if (! $platform) {
            abort(404, 'Platform not found.');
        }

        $data = CreatePlatformPageData::fromArray($request->validated(), $platform->id);
        $page = $this->createPage->execute($data);

        return ApiResponse::created(['page' => $page]);
    }

    public function update(UpdatePlatformPageRequest $request, PlatformPage $platformPage)
    {
        $this->ensureBelongsToPlatform($platformPage);

        $updated = $this->updatePage->execute($platformPage, $request->validated());

        return ApiResponse::updated(['page' => $updated]);
    }

    public function destroy(PlatformPage $platformPage)
    {
        $this->ensureBelongsToPlatform($platformPage);

        $this->deletePage->execute($platformPage);

        return ApiResponse::deleted();
    }

    private function ensureBelongsToPlatform(PlatformPage $platformPage): void
    {
        $platform = $this->tenantContext->getPlatform();

        if (! $platform || $platformPage->platform_id !== $platform->id) {
            abort(403, 'Page does not belong to this platform.');
        }
    }
}
