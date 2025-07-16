<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'course_id'];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */



     public function scopeActive($query)
     {
         return $query->where('is_active', 1);
     }


    public function course()
    {
        return $this->belongsTo(\App\Models\Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(\App\Models\Lesson::class);
    }
}
