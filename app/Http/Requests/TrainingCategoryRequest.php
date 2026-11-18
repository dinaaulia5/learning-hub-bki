<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TrainingCategoryRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:20', Rule::unique('training_categories', 'code')->ignore($this->route('category')),],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
