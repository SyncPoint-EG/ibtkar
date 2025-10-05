<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $with = ['questions'];

    protected $fillable = [
        'title',
        'description',
        'lesson_id',
        'teacher_id',
        'course_id',
        'duration_minutes',
        'total_marks',
        'is_active',
        'start_date',
        'end_date',
        'pass_degree',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function isAvailable()
    {
        $now = now();

        return $this->is_active &&
            (! $this->start_date || $this->start_date <= $now) &&
            (! $this->end_date || $this->end_date >= $now);
    }
}
