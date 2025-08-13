<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeworkAttempt extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'homework_id', 'score'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }

    public function answers()
    {
        return $this->hasMany(HomeworkAnswer::class);
    }
}
