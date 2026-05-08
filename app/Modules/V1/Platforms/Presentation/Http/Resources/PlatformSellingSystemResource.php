<?php

namespace App\Modules\V1\Platforms\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlatformSellingSystemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'key'   => $this->system->value,
            'value' => $this->system->label(),
        ];
    }
}
