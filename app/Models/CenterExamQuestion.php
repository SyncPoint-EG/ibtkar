<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CenterExamQuestion extends Model
{
    protected $fillable = [
        'center_exam_id',
        'question_text',
        'question_type',
        'marks',
        'image',
        'correct_essay_answer',
    ];

    public function centerExam(): BelongsTo
    {
        return $this->belongsTo(CenterExam::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(CenterExamQuestionOption::class);
    }
}
