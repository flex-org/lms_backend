<?php

namespace App\Modules\V1\Editor\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlatformPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'active' => 'sometimes|boolean',
        ];
    }
}
