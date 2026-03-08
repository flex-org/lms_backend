<?php

namespace App\Modules\V1\Editor\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlatformSectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $section = $this->section;
        $structures = $this->whenLoaded('section', function () use ($section) {
            if (! $section?->relationLoaded('structures')) {
                return null;
            }

            $valuesMap = [];
            if ($this->relationLoaded('sectionValues')) {
                foreach ($this->sectionValues as $value) {
                    $valuesMap[$value->structure_id] = $value;
                }
            }

            return $section->structures->map(function ($structure) use ($valuesMap) {
                $value = $valuesMap[$structure->id] ?? null;

                return new StructureWithValueResource($structure, $value);
            });
        });

        return [
            'id' => $this->id,
            'section_id' => $this->section_id,
            'name' => $section?->translate()?->name,
            'active' => $this->active,
            'position' => $this->position,
            'structures' => $structures,
        ];
    }
}
