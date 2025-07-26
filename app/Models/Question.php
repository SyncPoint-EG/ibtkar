<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'exam_id',
        'question_text',
        'question_type',
        'marks',
        'order'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }

    public function getCorrectAnswer()
    {
        if ($this->question_type === 'multiple_choice') {
            return $this->options()->where('is_correct', true)->first();
        }
        return null;
    }
}
