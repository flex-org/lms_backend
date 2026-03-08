<?php

namespace App\Modules\V1\Admins\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UpdateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $assignableRoles = Role::where('guard_name', 'admins')
            ->where('name', '!=', 'owner')
            ->pluck('name')
            ->toArray();

        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:admins,email,' . $this->admin?->id,
            'phone' => 'sometimes|string|max:50|unique:admins,phone,' . $this->admin?->id,
            'password' => 'sometimes|string|min:8',
            'role' => ['sometimes', 'string', Rule::in($assignableRoles)],
        ];
    }
}
