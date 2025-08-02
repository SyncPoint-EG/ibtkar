<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot; // optional, better to extend Pivot

class SubjectTeacher extends Pivot  // or extends Model if you prefer
{
    protected $table = 'subject_teacher';

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'stage_id',
        'grade_id',
        'division_id',
    ];

    // Define relationships

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
