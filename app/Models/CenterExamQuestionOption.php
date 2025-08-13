<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CenterExamQuestionOption extends Model
{
    protected $fillable = [
        'center_exam_question_id',
        'option_text',
        'is_correct',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(CenterExamQuestion::class, 'center_exam_question_id');
    }
}
