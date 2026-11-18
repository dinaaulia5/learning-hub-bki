<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TrainingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $training = $this->route('training');

        return [
            'category_id' => [
                'required',
                'uuid',
                Rule::exists('training_categories', 'id')
                    ->whereNull('deleted_at'),
            ],

            'type_id' => [
                'required',
                'uuid',
                Rule::exists('training_types', 'id')
                    ->whereNull('deleted_at'),
            ],

            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('trainings', 'code')
                    ->ignore($training?->id),
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

            'level' => [
                'nullable',
                'string',
                'max:255',
            ],

            'duration_minutes' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'capacity' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'is_mandatory' => [
                'required',
                'boolean',
            ],

            'status' => [
                'required',
                Rule::in([
                    'DRAFT',
                    'PUBLISHED',
                    'ONGOING',
                    'COMPLETED',
                    'CANCELLED',
                ]),
            ],

            'thumbnail' => [
                'nullable',
                'string',
                'max:255',
            ],

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'registration_start' => [
                'nullable',
                'date',
            ],

            'registration_end' => [
                'nullable',
                'date',
                'after_or_equal:registration_start',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' =>
            'Training category wajib dipilih.',

            'category_id.uuid' =>
            'Training category tidak valid.',

            'category_id.exists' =>
            'Training category tidak ditemukan.',

            'type_id.required' =>
            'Training type wajib dipilih.',

            'type_id.uuid' =>
            'Training type tidak valid.',

            'type_id.exists' =>
            'Training type tidak ditemukan.',

            'code.required' =>
            'Kode training wajib diisi.',

            'code.unique' =>
            'Kode training sudah digunakan.',

            'title.required' =>
            'Nama training wajib diisi.',

            'end_date.after_or_equal' =>
            'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',

            'registration_end.after_or_equal' =>
            'Batas akhir pendaftaran harus setelah atau sama dengan awal pendaftaran.',

            'duration_minutes.min' =>
            'Durasi training minimal 1 menit.',

            'capacity.min' =>
            'Kapasitas peserta minimal 1.',
        ];
    }
}
