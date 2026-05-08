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
            'domain' => [
                'required',
                'string',
                'min:3',
                'max:63',
                'lowercase',
                'unique:platforms,domain',
                'regex:/^(?!-)(?!.*--)[a-z0-9-]+(?<!-)$/',
            ],
            'storage' => 'required|integer|min:50',
            'capacity' => 'required|integer|min:100',
            'mobile_app' => 'sometimes|boolean',
            'features' => 'required|array|min:1',
            'features.*' => 'required|string|distinct',
        ];
    }
}
