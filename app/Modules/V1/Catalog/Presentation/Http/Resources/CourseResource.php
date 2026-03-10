<?php

namespace App\Modules\V1\Catalog\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->translate()?->title,
            'description' => $this->translate()?->description,
            'image_url' => $this->getFirstMediaUrl('image') ?: null,
            'price' => $this->price,
            'active' => $this->active,
            'category_id' => $this->category_id,
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
