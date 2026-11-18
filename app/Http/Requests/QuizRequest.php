<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class QuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        foreach (
            [
                'shuffle_questions',
                'shuffle_options',
                'is_published',
            ] as $field
        ) {
            if (! $this->exists($field)) {
                $this->merge([$field => false]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'training_id' => [
                'required',
                'uuid',
                'exists:trainings,id',
            ],

            'module_id' => [
                Rule::requiredIf(
                    $this->input('assessment_type') === 'MODULE_QUIZ'
                ),
                'nullable',
                'uuid',
                Rule::exists('training_modules', 'id')
                    ->where('training_id', $this->input('training_id')),
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'assessment_type' => [
                'required',
                Rule::in([
                    'PRE_TEST',
                    'MODULE_QUIZ',
                    'POST_TEST',
                ]),
            ],

            'passing_score' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'duration_minutes' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'max_attempts' => [
                'required',
                'integer',
                'min:1',
            ],

            'shuffle_questions' => [
                'sometimes',
                'boolean',
            ],

            'shuffle_options' => [
                'sometimes',
                'boolean',
            ],

            'show_explanation_policy' => [
                'required',
                Rule::in([
                    'IMMEDIATE',
                    'AFTER_SUBMIT',
                    'NEVER',
                ]),
            ],

            'is_published' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $type = $this->input('assessment_type');
            $moduleId = $this->input('module_id');

            if (
                in_array($type, ['PRE_TEST', 'POST_TEST'], true)
                && filled($moduleId)
            ) {
                $validator->errors()->add(
                    'module_id',
                    'Pre-test dan Post-test harus berada pada level training.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'training_id.required' =>
            'Training wajib dipilih.',

            'training_id.exists' =>
            'Training yang dipilih tidak ditemukan.',

            'module_id.required' =>
            'Module wajib dipilih untuk Quiz per Module.',

            'module_id.exists' =>
            'Module tidak ditemukan atau bukan bagian dari training yang dipilih.',

            'title.required' =>
            'Judul quiz wajib diisi.',

            'assessment_type.required' =>
            'Jenis assessment wajib dipilih.',

            'passing_score.required' =>
            'Passing score wajib diisi.',

            'passing_score.min' =>
            'Passing score minimal 0.',

            'passing_score.max' =>
            'Passing score maksimal 100.',

            'max_attempts.required' =>
            'Maksimal percobaan wajib diisi.',

            'show_explanation_policy.required' =>
            'Kebijakan penjelasan jawaban wajib dipilih.',
        ];
    }
}
