<?php

namespace App\Modules\V1\Editor\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSectionValuesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'locale' => 'required|string|in:en,ar',
            'values' => 'required|array|min:1',
            'values.*.structure_id' => 'required|integer|exists:structures,id',
            'values.*.value' => 'present',
        ];
    }
}
