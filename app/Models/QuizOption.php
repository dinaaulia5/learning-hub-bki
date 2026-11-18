<?php

namespace App\Models\Assessment;

use App\Models\QuizAnswer;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizOption extends Model
{
    use HasUuids;

    protected $table = 'quiz_options';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'sequence' => 'integer',
        ];
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(
            QuizQuestion::class,
            'question_id'
        );
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'option_id');
    }
}
