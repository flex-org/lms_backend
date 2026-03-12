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

            return $this->buildStructuresMap($section);
        });

        return [
            'id' => $this->id,
            'section_id' => $this->section_id,
            'name' => $section?->translate()?->name,
            'key' => $section?->key,
            'active' => $this->active,
            'position' => $this->position,
            'structures' => $structures,
        ];
    }

    public function buildStructuresMap($section): \stdClass
    {
        $valuesMap = [];
        if ($this->relationLoaded('sectionValues')) {
            foreach ($this->sectionValues as $value) {
                $valuesMap[$value->structure_id] = $value;
            }
        }

        $keyed = $section->structures->mapWithKeys(function ($structure) use ($valuesMap) {
            $value = $valuesMap[$structure->id] ?? null;

            return [$structure->name => new StructureWithValueResource($structure, $value)];
        });

        return (object) $keyed->toArray();
    }
}
