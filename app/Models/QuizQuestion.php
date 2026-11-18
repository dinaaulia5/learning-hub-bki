<?php

namespace App\Models\Assessment;

use App\Models\QuizAnswer;
use App\Models\QuizOption;
use App\Models\QuizQuestionItem;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    use HasUuids;

    protected $table = 'quiz_questions';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'ai_generated' => 'boolean',
        ];
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuizOption::class, 'question_id')
            ->orderBy('sequence');
    }

    public function quizItems(): HasMany
    {
        return $this->hasMany(
            QuizQuestionItem::class,
            'question_id'
        );
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'question_id');
    }
}
