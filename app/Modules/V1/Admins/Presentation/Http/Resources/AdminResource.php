<?php

namespace App\Modules\V1\Admins\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
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
            'role' => $this->getRoleNames()->first(),
            'permissions' => $this->getAllPermissions()
                ->pluck('name')
                ->toArray(),
            'platform_features' => $this->platform->getAllPermissions()->pluck('name')->toArray(),
        ];
    }
}
