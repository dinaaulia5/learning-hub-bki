<?php

namespace App\Models\Assessment;

use App\Models\QuizAttempt;
use App\Models\QuizQuestionItem;
use App\Models\Training;
use App\Models\TrainingModule;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasUuids;

    protected $table = 'quizzes';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'passing_score' => 'decimal:2',
            'shuffle_questions' => 'boolean',
            'shuffle_options' => 'boolean',
            'is_published' => 'boolean',
            'duration_minutes' => 'integer',
            'max_attempts' => 'integer',
        ];
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(TrainingModule::class, 'module_id');
    }

    public function questionItems(): HasMany
    {
        return $this->hasMany(QuizQuestionItem::class, 'quiz_id')
            ->orderBy('sequence');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class, 'quiz_id');
    }
}
