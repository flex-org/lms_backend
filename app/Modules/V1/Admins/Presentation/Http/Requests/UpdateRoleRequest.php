<?php

namespace App\Modules\V1\Admins\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255|unique:roles,name,' . $this->route('role'),
            'permissions' => 'sometimes|array|min:1',
            'permissions.*' => 'string|exists:permissions,name',
        ];
    }
}
