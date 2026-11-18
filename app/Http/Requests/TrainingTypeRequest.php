<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TrainingTypeRequest extends FormRequest
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
        $trainingTypeId = $this->route('training_type')?->id;

        return [
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('training_types', 'code')->ignore($trainingTypeId)],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
