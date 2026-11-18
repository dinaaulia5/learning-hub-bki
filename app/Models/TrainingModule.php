<?php

namespace App\Models;

use App\Traits\HasFilter;
use App\Traits\HasSorting;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingModule extends Model
{
    use HasSorting, HasFilter, HasUuids, SoftDeletes;

    protected $fillable = [
        'training_id',
        'title',
        'description',
        'sequence',
        'duration_minutes',
        'is_required',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sequence' => 'integer',
            'duration_minutes' => 'integer',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(TrainingMaterial::class, 'module_id')
            ->orderBy('sequence');
    }
}
