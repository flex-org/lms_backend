<?php

namespace App\Modules\V1\Catalog\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'translations' => 'sometimes|array',
            'translations.en' => 'sometimes|array',
            'translations.en.name' => 'sometimes|string|max:255',
            'translations.en.description' => 'nullable|string',
            'translations.ar' => 'sometimes|array',
            'translations.ar.name' => 'sometimes|string|max:255',
            'translations.ar.description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'nullable|numeric|min:0',
            'active' => 'sometimes|boolean',
        ];
    }
}
