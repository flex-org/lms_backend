<?php

namespace App\Modules\V1\Editor\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlatformSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform_page_id' => 'required|integer|exists:platform_pages,id',
            'section_id' => 'required|integer|exists:sections,id',
            'active' => 'sometimes|boolean',
            'position' => 'sometimes|integer|min:0',
        ];
    }
}
