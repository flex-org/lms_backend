<?php

namespace App\Modules\V1\Editor\Presentation\Http\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Domain\Contracts\TenantContextInterface;
use App\Modules\V1\Editor\Application\UseCases\ListPlatformSectionsUseCase;
use App\Modules\V1\Editor\Application\UseCases\ReorderSectionsUseCase;
use App\Modules\V1\Editor\Application\UseCases\UpdatePlatformSectionUseCase;
use App\Modules\V1\Editor\Application\UseCases\UpdateSectionValuesUseCase;
use App\Modules\V1\Editor\Domain\Models\PlatformPage;
use App\Modules\V1\Editor\Domain\Models\PlatformSection;
use App\Modules\V1\Editor\Presentation\Http\Requests\ReorderSectionsRequest;
use App\Modules\V1\Editor\Presentation\Http\Requests\UpdatePlatformSectionRequest;
use App\Modules\V1\Editor\Presentation\Http\Requests\UpdateSectionValuesRequest;
use App\Modules\V1\Editor\Presentation\Http\Resources\PlatformSectionResource;

class PlatformSectionController extends Controller
{
    public function __construct(
        private readonly ListPlatformSectionsUseCase $listSections,
        private readonly UpdatePlatformSectionUseCase $updateSection,
        private readonly ReorderSectionsUseCase $reorderSections,
        private readonly UpdateSectionValuesUseCase $updateValues,
        private readonly TenantContextInterface $tenantContext,
    ) {}

    public function index(string $pageKey)
    {
        $platformPage = $this->resolvePlatformPage($pageKey);

        $sections = $this->listSections->execute($platformPage->id);

        $keyed = $sections->mapWithKeys(function ($section) {
            $key = $section->section?->key ?? $section->id;

            return [$key => new PlatformSectionResource($section)];
        });

        return ApiResponse::success((object) $keyed->toArray());
    }

    public function update(UpdatePlatformSectionRequest $request, PlatformSection $platformSection)
    {
        $updated = $this->updateSection->execute($platformSection, $request->validated());

        return ApiResponse::updated(['section' => new PlatformSectionResource($updated)]);
    }

    public function reorder(ReorderSectionsRequest $request, string $pageKey)
    {
        $platformPage = $this->resolvePlatformPage($pageKey);

        $this->reorderSections->execute($platformPage->id, $request->validated('ordered_ids'));

        return ApiResponse::updated();
    }

    public function structuresByKey(string $pageKey, string $sectionKey)
    {
        $platformPage = $this->resolvePlatformPage($pageKey);

        $platformSection = PlatformSection::where('platform_page_id', $platformPage->id)
            ->whereHas('section', fn ($q) => $q->where('key', $sectionKey))
            ->with(['section.structures.translations', 'sectionValues.translations'])
            ->firstOrFail();

        $resource = new PlatformSectionResource($platformSection);
        $structuresMap = $resource->buildStructuresMap($platformSection->section);

        return ApiResponse::success($structuresMap);
    }

    public function updateValues(UpdateSectionValuesRequest $request, PlatformSection $platformSection)
    {
        $updated = $this->updateValues->execute(
            $platformSection,
            $request->validated('locale'),
            $request->validated('values'),
        );

        return ApiResponse::updated(['section' => new PlatformSectionResource($updated)]);
    }

    private function resolvePlatformPage(string $pageKey): PlatformPage
    {
        return PlatformPage::whereHas('page', fn ($q) => $q->where('key', $pageKey))
            ->where('platform_id', $this->tenantContext->getPlatformId())
            ->firstOrFail();
    }
}
