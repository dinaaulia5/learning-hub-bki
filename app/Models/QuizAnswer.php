<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAnswer extends Model
{
    use HasUuids;

    protected $table = 'quiz_answers';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'points' => 'decimal:2',
        ];
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, 'attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(
            QuizQuestion::class,
            'question_id'
        );
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(
            QuizOption::class,
            'option_id'
        );
    }
}
