<?php

namespace App\Models\Assessment;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizQuestionItem extends Model
{
    use HasUuids;

    protected $table = 'quiz_question_items';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'sequence' => 'integer',
            'weight_points' => 'decimal:2',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(
            QuizQuestion::class,
            'question_id'
        );
    }
}
