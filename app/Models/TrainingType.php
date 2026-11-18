<?php

namespace App\Models;

use App\Traits\HasFilter;
use App\Traits\HasSorting;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingType extends Model
{
    use HasFilter, HasSorting, HasUuids, SoftDeletes;
    protected $table = 'training_types';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];
}
