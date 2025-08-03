<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'phone', 'other_phone', 'bio', 'image', 'rate','password'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */


     public function scopeActive($query)
     {
         return $query->where('is_active', 1);
     }


    public function subjects()
    {
        return $this->belongsToMany(Subject::class)
            ->withPivot(['stage_id','grade_id','division_id'])
            ;
    }
    public function subjectTeacherAssignments()
    {
        return $this->hasMany(SubjectTeacher::class, 'teacher_id')->with(['subject', 'grade', 'stage', 'division']);
    }

    public function stages()
    {
        return $this->belongsToMany(\App\Models\Stage::class);
    }

    public function grades()
    {
        return $this->belongsToMany(\App\Models\Grade::class);
    }

    public function divisions()
    {
        return $this->belongsToMany(\App\Models\Division::class);
    }

    public function getImageAttribute()
    {
        if ($this->attributes['image']) {
            // Check if it's a full URL (for external images)
            if (filter_var($this->attributes['image'], FILTER_VALIDATE_URL)) {
                return $this->attributes['image'];
            }

            // Check if file exists in storage
            if (Storage::disk('public')->exists($this->attributes['image'])) {
                return asset(Storage::url($this->attributes['image']));
            }
        }

        // Return default avatar if no image
        return asset('dashboard/app-assets/images/portrait/small/avatar-s-1.png');
    }

    /**
     * Set the user's image.
     * This is a setter that handles image upload
     */
    public function setImageAttribute($value)
    {
        // If value is null or empty, keep existing image
        if (empty($value)) {
            return;
        }

        // If it's an uploaded file
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($this->attributes['image'] ?? null) {
                Storage::disk('public')->delete($this->attributes['image']);
            }

            // Store new image
            $path = $value->store('users/avatars', 'public');
            $this->attributes['image'] = $path;
        }
        // If it's a string path
        else if (is_string($value)) {
            $this->attributes['image'] = $value;
        }
    }

    public function courses()
    {
        return $this->hasMany(\App\Models\Course::class);
    }

    public function students()
    {
        return Student::first();
    }

    public function getLessonsCountAttribute()
    {
        $teacherStages = $this->courses->pluck('stage_id');
        $teacherGrades = $this->courses->pluck('grade_id');
        $teacherDivisions = $this->courses->pluck('division_id');
        return Lesson::whereHas('chapter.course', function ($q) use ($teacherDivisions, $teacherStages, $teacherGrades) {
            $q->wherein('stage_id',$teacherStages)->whereIn('grade_id',$teacherGrades)
            ->whereIn('division_id',$teacherDivisions);
        })->count();
    }

    public function getStudentsCountAttribute()
    {
        $teacherStages = $this->courses->pluck('stage_id');
        $teacherGrades = $this->courses->pluck('grade_id');
        $teacherDivisions = $this->courses->pluck('division_id');
        return Student::where(function ($q) use ($teacherDivisions, $teacherStages, $teacherGrades) {
            $q->whereIn('stage_id',$teacherStages)->whereIn('grade_id',$teacherGrades)
                ->whereIn('division_id',$teacherDivisions);
        })
        ->count();
    }
}
