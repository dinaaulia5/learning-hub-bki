<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TrainingModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'training_id' => [
                'required',
                'uuid',
                Rule::exists('trainings', 'id')
                    ->whereNull('deleted_at'),
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

            'sequence' => [
                'required',
                'integer',
                'min:1',
            ],

            'duration_minutes' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'is_required' => [
                'required',
                'boolean',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'training_id.required' =>
            'Training wajib dipilih.',

            'training_id.exists' =>
            'Training tidak ditemukan.',

            'title.required' =>
            'Judul modul wajib diisi.',

            'sequence.required' =>
            'Urutan modul wajib diisi.',

            'sequence.min' =>
            'Urutan modul minimal 1.',

            'duration_minutes.min' =>
            'Durasi minimal 1 menit.',
        ];
    }
}
