<?php

namespace App\Models;

use App\Enums\LearningType;
use App\Traits\HasFilter;
use App\Traits\HasSorting;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Training extends Model
{
    use HasFilter, HasSorting, HasUuids, SoftDeletes;
    protected $table = 'trainings';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'category_id',
        'type_id',
        'code',
        'title',
        'slug',
        'description',
        'level',
        'duration_minutes',
        'capacity',
        'is_mandatory',
        'status',
        'thumbnail',
        'start_date',
        'end_date',
        'registration_start',
        'registration_end',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_mandatory' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
            'registration_start' => 'date',
            'registration_end' => 'date',
        ];
    }

    /**
     * Relasi ke Training Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(
            TrainingCategory::class,
            'category_id'
        );
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(
            TrainingType::class,
            'type_id'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function modules(): HasMany
    {
        return $this->hasMany(TrainingModule::class, 'training_id')
            ->orderBy('sequence');
    }
}
