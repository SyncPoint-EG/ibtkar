<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CenterExamAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'center_exam_attempt_id',
        'student_id',
        'center_exam_id',
        'question_id',
        'option_id',
        'essay_answer',
        'is_correct',
    ];

    public function attempt()
    {
        return $this->belongsTo(CenterExamAttempt::class, 'center_exam_attempt_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function centerExam()
    {
        return $this->belongsTo(CenterExam::class);
    }

    public function question()
    {
        return $this->belongsTo(CenterExamQuestion::class, 'question_id');
    }

    public function option()
    {
        return $this->belongsTo(CenterExamQuestionOption::class, 'option_id');
    }
}
