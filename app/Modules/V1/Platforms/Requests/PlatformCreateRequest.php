<?php

namespace App\Modules\V1\Platforms\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlatformCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string',
            'phone' => 'required|string',
            'domain' => 'required|string|max:255|unique:platforms,domain',
            'storage' => 'required|integer|min:50',
            'capacity' => 'required|integer|min:100',
            'selling_systems' => 'required|array',
            'selling_systems.*' => 'required|integer',
            'features' => 'required|array',
            'features.*' => 'required|integer',
        ];
    }
}
