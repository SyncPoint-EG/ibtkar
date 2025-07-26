<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeworkAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'homework_id',
        'homework_question_id',
        'user_id',
        'answer_text',
        'selected_option_id',
        'is_correct',
        'marks_obtained'
    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }

    public function question()
    {
        return $this->belongsTo(HomeworkQuestion::class, 'homework_question_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function selectedOption()
    {
        return $this->belongsTo(HomeworkQuestionOption::class, 'selected_option_id');
    }
}
