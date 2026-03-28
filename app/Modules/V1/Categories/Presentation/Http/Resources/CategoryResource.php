<?php

namespace App\Modules\V1\Catalog\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->translate()?->name,
            'description' => $this->translate()?->description,
            'image_url' => $this->getFirstMediaUrl('image') ?: null,
            'price' => $this->price,
            'active' => $this->active,
            'courses_count' => $this->whenCounted('courses'),
        ];
    }
}
