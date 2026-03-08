<?php

namespace App\Modules\V1\Editor\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlatformPageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'page_id' => $this->page_id,
            'key' => $this->page?->key,
            'name' => $this->page?->translate()?->name,
            'active' => $this->active,
            'sections_count' => $this->whenCounted('platformSections'),
            'sections' => PlatformSectionResource::collection(
                $this->whenLoaded('platformSections')
            ),
        ];
    }
}
