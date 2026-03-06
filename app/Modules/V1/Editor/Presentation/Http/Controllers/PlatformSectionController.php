<?php

namespace App\Modules\V1\Editor\Presentation\Http\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\Editor\Application\DTOs\CreatePlatformSectionData;
use App\Modules\V1\Editor\Application\UseCases\CreatePlatformSectionUseCase;
use App\Modules\V1\Editor\Application\UseCases\DeletePlatformSectionUseCase;
use App\Modules\V1\Editor\Application\UseCases\ListPlatformSectionsUseCase;
use App\Modules\V1\Editor\Application\UseCases\ReorderSectionsUseCase;
use App\Modules\V1\Editor\Application\UseCases\UpdatePlatformSectionUseCase;
use App\Modules\V1\Editor\Domain\Models\PlatformSection;
use App\Modules\V1\Editor\Presentation\Http\Requests\ReorderSectionsRequest;
use App\Modules\V1\Editor\Presentation\Http\Requests\StorePlatformSectionRequest;
use App\Modules\V1\Editor\Presentation\Http\Requests\UpdatePlatformSectionRequest;

class PlatformSectionController extends Controller
{
    public function __construct(
        private readonly ListPlatformSectionsUseCase $listSections,
        private readonly CreatePlatformSectionUseCase $createSection,
        private readonly UpdatePlatformSectionUseCase $updateSection,
        private readonly DeletePlatformSectionUseCase $deleteSection,
        private readonly ReorderSectionsUseCase $reorderSections,
    ) {
    }

    public function index(int $platformPageId)
    {
        $sections = $this->listSections->execute($platformPageId);

        return ApiResponse::success($sections);
    }

    public function store(StorePlatformSectionRequest $request)
    {
        $data = CreatePlatformSectionData::fromArray($request->validated());
        $section = $this->createSection->execute($data);

        return ApiResponse::created(['section' => $section]);
    }

    public function update(UpdatePlatformSectionRequest $request, PlatformSection $platformSection)
    {
        $updated = $this->updateSection->execute($platformSection, $request->validated());

        return ApiResponse::updated(['section' => $updated]);
    }

    public function destroy(PlatformSection $platformSection)
    {
        $this->deleteSection->execute($platformSection);

        return ApiResponse::deleted();
    }

    public function reorder(ReorderSectionsRequest $request, int $platformPageId)
    {
        $this->reorderSections->execute($platformPageId, $request->validated('ordered_ids'));

        return ApiResponse::updated();
    }
}
