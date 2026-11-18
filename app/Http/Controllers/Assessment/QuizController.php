<?php

namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class QuizController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'training_id' => [
                'required',
                'uuid',
                'exists:trainings,id',
            ],
            'module_id' => [
                'nullable',
                'uuid',
                'exists:training_modules,id',
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
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
                'required',
                'integer',
                'min:1',
            ],
            'max_attempts' => [
                'required',
                'integer',
                'min:1',
            ],
            'shuffle_questions' => ['required', 'boolean'],
            'shuffle_options' => ['required', 'boolean'],
            'show_explanation_policy' => [
                'required',
                Rule::in([
                    'IMMEDIATE',
                    'AFTER_SUBMIT',
                    'NEVER',
                ]),
            ],
            'is_published' => ['required', 'boolean'],

            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question' => [
                'required',
                'string',
            ],
            'questions.*.type' => [
                'required',
                'string',
            ],
            'questions.*.difficulty' => [
                'required',
                'string',
            ],
            'questions.*.explanation' => [
                'nullable',
                'string',
            ],
            'questions.*.points' => [
                'required',
                'numeric',
                'min:0',
            ],
            'questions.*.options' => [
                'required',
                'array',
                'min:2',
            ],
            'questions.*.options.*.option_text' => [
                'required',
                'string',
            ],
            'questions.*.options.*.is_correct' => [
                'required',
                'boolean',
            ],
        ]);

        // Post Test dan Pre Test berlaku untuk seluruh training.
        if ($validated['assessment_type'] !== 'MODULE_QUIZ') {
            $validated['module_id'] = null;
        }

        // Pastikan modul berasal dari training yang sama.
        if ($validated['assessment_type'] === 'MODULE_QUIZ') {
            $moduleExists = DB::table('training_modules')
                ->where('id', $validated['module_id'])
                ->where('training_id', $validated['training_id'])
                ->exists();

            if (! $moduleExists) {
                return back()->withErrors([
                    'module_id' =>
                    'Modul tidak sesuai dengan training.',
                ]);
            }
        }

        // Validasi satu jawaban benar untuk setiap soal.
        foreach ($validated['questions'] as $index => $question) {
            $correctCount = collect($question['options'])
                ->where('is_correct', true)
                ->count();

            if ($correctCount !== 1) {
                return back()->withErrors([
                    "questions.$index.options" =>
                    'Setiap soal harus memiliki tepat satu jawaban benar.',
                ]);
            }
        }

        DB::transaction(function () use ($validated) {
            $quizId = (string) Str::uuid();

            // 1. Simpan konfigurasi Quiz.
            DB::table('quizzes')->insert([
                'id' => $quizId,
                'training_id' => $validated['training_id'],
                'module_id' => $validated['module_id'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'assessment_type' => $validated['assessment_type'],
                'passing_score' => $validated['passing_score'],
                'duration_minutes' => $validated['duration_minutes'],
                'max_attempts' => $validated['max_attempts'],
                'shuffle_questions' => $validated['shuffle_questions'],
                'shuffle_options' => $validated['shuffle_options'],
                'show_explanation_policy' =>
                $validated['show_explanation_policy'],
                'is_published' => $validated['is_published'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2–4. Simpan soal, opsi, dan relasinya.
            foreach ($validated['questions'] as $index => $item) {
                $questionId = (string) Str::uuid();

                DB::table('quiz_questions')->insert([
                    'id' => $questionId,
                    'question' => $item['question'],
                    'type' => $item['type'],
                    'difficulty' => $item['difficulty'],
                    'explanation' => $item['explanation'] ?? null,
                    'source' => 'MANUAL',
                    'source_document_id' => null,
                    'ai_generated' => false,
                    'generation_prompt' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($item['options'] as $optionIndex => $option) {
                    DB::table('quiz_options')->insert([
                        'id' => (string) Str::uuid(),
                        'question_id' => $questionId,
                        'option_text' => $option['option_text'],
                        'is_correct' => $option['is_correct'],
                        'sequence' => $optionIndex + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('quiz_question_items')->insert([
                    'id' => (string) Str::uuid(),
                    'quiz_id' => $quizId,
                    'question_id' => $questionId,
                    'sequence' => $item['sequence'] ?? ($index + 1),
                    'weight_points' => $item['points'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return back()->with(
            'success',
            'Post Test berhasil disimpan.'
        );
    }
}
