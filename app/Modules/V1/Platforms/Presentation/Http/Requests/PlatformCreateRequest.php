<?php

namespace App\Modules\V1\Platforms\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlatformCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|string|min:8',
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
