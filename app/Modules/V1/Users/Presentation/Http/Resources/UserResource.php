<?php

namespace App\Modules\V1\Users\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'platform_id' => $this->platform_id,
            'role' => 'user',
            'platform_features' => $this->platform->getAllPermissions()->pluck('name')->toArray(),

        ];
    }
}
