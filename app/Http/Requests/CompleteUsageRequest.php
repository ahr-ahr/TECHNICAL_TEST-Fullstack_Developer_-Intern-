<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CompleteUsageRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_km' => 'required|integer|min:0',
            'end_km' => 'required|integer|gte:start_km',

            'liters' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',

            'service_description' => 'nullable|string',
            'service_cost' => 'nullable|numeric|min:0',
        ];
    }
}
