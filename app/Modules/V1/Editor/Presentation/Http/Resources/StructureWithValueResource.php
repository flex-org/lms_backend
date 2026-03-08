<?php

namespace App\Modules\V1\Editor\Presentation\Http\Resources;

use App\Modules\V1\Editor\Domain\Models\Structure;
use App\Modules\V1\Editor\Domain\Models\Values;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StructureWithValueResource extends JsonResource
{
    private ?Values $sectionValue;

    public function __construct(Structure $structure, ?Values $sectionValue)
    {
        parent::__construct($structure);
        $this->sectionValue = $sectionValue;
    }

    public function toArray(Request $request): array
    {
        $value = $this->resolveValue();

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'is_array' => $this->is_array,
            'label' => $this->translate()?->label,
            'placeholder' => $this->translate()?->placeholder,
            'value_id' => $this->sectionValue?->id,
            'value' => $value,
        ];

        if ($this->is_array) {
            $data['max'] = (int) $this->max;
        }

        if ($this->type === 'composite') {
            $data['fields'] = $this->extractCompositeFields($value);
        }

        return $data;
    }

    private function resolveValue(): mixed
    {
        if (! $this->sectionValue) {
            return null;
        }

        return $this->sectionValue->translate(app()->getLocale())?->value;
    }

    /**
     * Extract the sub-field keys from the first composite item
     * so the frontend knows which inputs to render.
     */
    private function extractCompositeFields(mixed $value): array
    {
        if ($value === null) {
            return [];
        }

        if ($this->is_array && is_array($value) && isset($value[0]) && is_array($value[0])) {
            return array_keys($value[0]);
        }

        if (! $this->is_array && is_array($value)) {
            return array_keys($value);
        }

        return [];
    }
}
