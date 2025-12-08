<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamAttempt extends Model
{
    protected $fillable = [
        'exam_id',
        'student_id',
        'started_at',
        'completed_at',
        'score',
        'total_marks',
        'is_submitted',
        'is_passed',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_submitted' => 'boolean',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }

    public function getPercentageScore()
    {
        return $this->total_marks > 0 ? round(($this->score / $this->total_marks) * 100, 2) : 0;
    }

    public function getIsPassedAttribute($value)
    {
        return $value == 1 ? true : false;
    }
}
