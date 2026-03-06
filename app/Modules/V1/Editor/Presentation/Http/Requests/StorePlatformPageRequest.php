<?php

namespace App\Modules\V1\Editor\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlatformPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page_id' => 'required|integer|exists:pages,id',
            'active' => 'sometimes|boolean',
        ];
    }
}
