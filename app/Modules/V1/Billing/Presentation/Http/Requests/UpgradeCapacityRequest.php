<?php

namespace App\Modules\V1\Billing\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpgradeCapacityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'capacity' => ['required', 'integer', 'min:1'],
        ];
    }
}

