<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeworkQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'homework_id',
        'question_text',
        'question_type',
        'marks',
        'order',
        'is_required'
    ];

    protected $casts = [
        'is_required' => 'boolean'
    ];

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }

    public function options()
    {
        return $this->hasMany(HomeworkQuestionOption::class)->orderBy('order');
    }

    public function answers()
    {
        return $this->hasMany(HomeworkAnswer::class);
    }

    public function getCorrectOption()
    {
        return $this->options()->where('is_correct', true)->first();
    }
}
