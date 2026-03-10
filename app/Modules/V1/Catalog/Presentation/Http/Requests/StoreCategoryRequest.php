<?php

namespace App\Modules\V1\Catalog\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'translations' => 'required|array',
            'translations.en' => 'required|array',
            'translations.en.name' => 'required|string|max:255',
            'translations.en.description' => 'nullable|string',
            'translations.ar' => 'nullable|array',
            'translations.ar.name' => 'required_with:translations.ar|string|max:255',
            'translations.ar.description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'nullable|numeric|min:0',
            'active' => 'sometimes|boolean',
        ];
    }
}
