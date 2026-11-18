<?php

namespace App\Models\Assessment;

use App\Models\Employee;
use App\Models\Enrollment;
use App\Models\QuizAnswer;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    use HasUuids;

    protected $table = 'quiz_attempts';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'attempt_number' => 'integer',
            'time_spent_seconds' => 'integer',
            'score' => 'decimal:2',
            'passed' => 'boolean',
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'attempt_id');
    }
}
