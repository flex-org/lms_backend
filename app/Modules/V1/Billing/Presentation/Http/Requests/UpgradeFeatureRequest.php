<?php

namespace App\Modules\V1\Billing\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpgradeFeatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'feature_id' => ['required', 'integer', 'exists:features,id'],
        ];
    }
}

