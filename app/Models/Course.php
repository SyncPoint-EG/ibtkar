<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'year', 'teacher_id', 'education_type_id', 'stage_id', 'grade_id', 'division_id', 'semister_id', 'subject_id'];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */


     public function scopeActive($query)
     {
         return $query->where('is_active', 1);
     }


    public function educationType()
    {
        return $this->belongsTo(\App\Models\EducationType::class);
    }

    public function stage()
    {
        return $this->belongsTo(\App\Models\Stage::class);
    }

    public function grade()
    {
        return $this->belongsTo(\App\Models\Grade::class);
    }

    public function division()
    {
        return $this->belongsTo(\App\Models\Division::class);
    }

    public function semister()
    {
        return $this->belongsTo(\App\Models\Semister::class);
    }

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}
