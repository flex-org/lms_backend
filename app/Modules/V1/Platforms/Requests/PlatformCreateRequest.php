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
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|string',
            'phone' => 'required|string|unique:admins,phone',
            'domain' => 'required|string|max:255|unique:platforms,domain',
            'storage' => 'required|integer|min:50',
            'capacity' => 'required|integer|min:100',
            'mobile_app' => 'sometimes|boolean',
            'selling_systems' => 'required|array|min:1',
            'selling_systems.*' => 'required|integer|distinct|exists:selling_systems,id',
            'features' => 'required|array|min:1',
            'features.*' => 'required|integer|distinct|exists:features,id',
        ];
    }
}
