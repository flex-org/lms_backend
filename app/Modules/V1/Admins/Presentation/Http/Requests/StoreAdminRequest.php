<?php

namespace App\Modules\V1\Admins\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class StoreAdminRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email',
            'phone' => 'required|string|max:50|unique:admins,phone',
            'password' => 'required|string|min:8',
            'role' => ['required', 'string', Rule::in($assignableRoles)],
        ];
    }
}
