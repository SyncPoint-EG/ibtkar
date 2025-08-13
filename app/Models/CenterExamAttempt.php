<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CenterExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'center_exam_id', 'score'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function centerExam()
    {
        return $this->belongsTo(CenterExam::class);
    }

    public function answers()
    {
        return $this->hasMany(CenterExamAnswer::class);
    }
}
