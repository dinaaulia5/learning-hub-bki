<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TrainingMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'type' => [
                'required',
                Rule::in([
                    'PDF',
                    'VIDEO',
                    'TEXT',
                    'LINK',
                    'QUIZ',
                ]),
            ],

            'file_path' => [
                'nullable',
                'string',
                'max:2048',
            ],

            'file_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'file_size' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'mime_type' => [
                'nullable',
                'string',
                'max:255',
            ],

            'video_url' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'sequence' => [
                'sometimes',
                'integer',
                'min:1',
            ],

            'duration_minutes' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'is_preview' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
