<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory;

    public $with = ['questions'];

    protected $fillable = [
        'title',
        'description',
        'lesson_id',
        'total_marks',
        'is_active',
        'due_date'
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function questions()
    {
        return $this->hasMany(HomeworkQuestion::class)->orderBy('order');
    }

    public function answers()
    {
        return $this->hasMany(HomeworkAnswer::class);
    }

    public function userAnswers($userId)
    {
        return $this->answers()->where('user_id', $userId);
    }

    public function attempts()
    {
        return $this->hasMany(HomeworkAttempt::class);
    }
}
