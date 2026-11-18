<?php

namespace App\Models;

use App\Traits\HasFilter;
use App\Traits\HasSorting;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingMaterial extends Model
{
    use HasFilter, HasSorting, HasUuids;
    // SoftDeletes;

    protected $fillable = [
        'module_id',
        'title',
        'description',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'video_url',
        'sequence',
        'duration_minutes',
        'is_preview',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'sequence' => 'integer',
            'duration_minutes' => 'integer',
            'is_preview' => 'boolean',
        ];
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(TrainingModule::class, 'module_id');
    }
}
