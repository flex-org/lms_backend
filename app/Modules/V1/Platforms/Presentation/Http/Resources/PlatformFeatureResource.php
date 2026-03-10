<?php

namespace App\Modules\V1\Platforms\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlatformFeatureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'key'         => $this->key,
            'icon'        => $this->icon,
            'name'        => $this->translate()?->name,
            'description' => $this->translate()?->description,
            'included'    => (bool) $this->included,
        ];
    }
}
