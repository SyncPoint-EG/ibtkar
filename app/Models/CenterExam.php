<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CenterExam extends Model
{
    protected $fillable = [
        'center_id',
        'stage_id',
        'grade_id',
        'division_id',
        'title',
        'description',
        'total_marks',
        'passing_marks',
        'duration_minutes',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function center(): BelongsTo
    {
        return $this->belongsTo(Center::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(CenterExamQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(CenterExamAttempt::class);
    }
}
