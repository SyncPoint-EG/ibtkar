<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class Teacher extends Authenticatable
{
    use HasApiTokens ,HasFactory, Notifiable;

    const DAYS_OF_WEEK = [
        1 => 'saturday',
        2 => 'sunday',
        3 => 'monday',
        4 => 'tuesday',
        5 => 'wednesday',
        6 => 'thursday',
        7 => 'friday',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'phone', 'other_phone', 'bio', 'image', 'rate', 'password', 'is_featured', 'uuid', 'website_image'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function devices()
    {
        return $this->morphMany(UserDevice::class, 'user');
    }

    public function routeNotificationForFcm($notification = null)
    {
        return $this->devices()->pluck('fcm_token')->toArray();
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($teacher) {
            do {
                $code = strtoupper(Str::random(8));
            } while (self::where('uuid', $code)->exists());

            $teacher->uuid = $code;
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeForStudent($query, Student $student)
    {
        return $query->whereHas('subjectTeacherAssignments', function ($q) use ($student) {
            $q->where('stage_id', $student->stage_id);
            $q->where('grade_id', $student->grade_id);
            if ($student->division_id) {
                $q->where(function ($qq) use ($student) {
                    $qq->where('division_id', $student->division_id)
                        ->orWhereNull('division_id');
                });
            }
        })
            ->orWhereHas('courses', function ($q) use ($student) {
                $q->where('stage_id', $student->stage_id);
                $q->where('grade_id', $student->grade_id);
                if ($student->division_id) {
                    $q->where(function ($qq) use ($student) {
                        $qq->where('division_id', $student->division_id)
                            ->orWhereNull('division_id');
                    });
                }
            });
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class)
            ->withPivot(['stage_id', 'grade_id', 'division_id', 'day_of_week', 'time']);
    }

    public function subjectTeacherAssignments()
    {
        return $this->hasMany(SubjectTeacher::class, 'teacher_id')->with(['subject', 'grade', 'stage', 'division']);
    }

    //    public function stages()
    //    {
    //        return $this->belongsToMany(\App\Models\Stage::class);
    //    }
    //
    //    public function grades()
    //    {
    //        return $this->belongsToMany(\App\Models\Grade::class);
    //    }
    //
    //    public function divisions()
    //    {
    //        return $this->belongsToMany(\App\Models\Division::class);
    //    }

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
        elseif (is_string($value)) {
            $this->attributes['image'] = $value;
        }
    }

    public function getWebsiteImageAttribute()
    {
        if ($this->attributes['website_image']) {
            // Check if it's a full URL (for external images)
            if (filter_var($this->attributes['website_image'], FILTER_VALIDATE_URL)) {
                return $this->attributes['website_image'];
            }

            // Check if file exists in storage
            if (Storage::disk('public')->exists($this->attributes['website_image'])) {
                return asset(Storage::url($this->attributes['website_image']));
            }
        }

        // Return default avatar if no image
        return asset('dashboard/app-assets/images/portrait/small/avatar-s-1.png');
    }

    /**
     * Set the user's website_image.
     * This is a setter that handles image upload
     */
    public function setWebsiteImageAttribute($value)
    {
        // If value is null or empty, keep existing image
        if (empty($value)) {
            return;
        }

        // If it's an uploaded file
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($this->attributes['website_image'] ?? null) {
                Storage::disk('public')->delete($this->attributes['website_image']);
            }

            // Store new image
            $path = $value->store('users/avatars', 'public');
            $this->attributes['website_image'] = $path;
        }
        // If it's a string path
        elseif (is_string($value)) {
            $this->attributes['website_image'] = $value;
        }
    }

    public function courses()
    {
        return $this->hasMany(\App\Models\Course::class);
    }

    // Get stages through courses
    public function stages()
    {
        return $this->hasManyThrough(Stage::class, SubjectTeacher::class, 'teacher_id', 'id', 'id', 'stage_id');
    }

    // Get grades through courses
    public function grades()
    {
        return $this->hasManyThrough(Grade::class, SubjectTeacher::class, 'teacher_id', 'id', 'id', 'grade_id');
    }

    // Get divisions through courses
    public function divisions()
    {
        return $this->hasManyThrough(Division::class, SubjectTeacher::class, 'teacher_id', 'id', 'id', 'division_id');
    }

    //    public function subjects()
    //    {
    //        return $this->hasManyThrough(Subject::class, Course::class, 'teacher_id', 'id', 'id', 'subject_id');
    //    }
    public function students()
    {
        return Student::first();
    }

    public function chapters()
    {
        return Chapter::whereIn('course_id', $this->courses()->pluck('id'));

    }

    public function lessons()
    {
        return Lesson::whereIn('chapter_id',
            Chapter::whereIn('course_id',
                $this->courses()->pluck('id')
            )->pluck('id')
        );
    }

    public function getLessonsCountAttribute()
    {
        $teacherStages = $this->courses->pluck('stage_id');
        $teacherGrades = $this->courses->pluck('grade_id');
        $teacherDivisions = $this->courses->pluck('division_id');
        return Lesson::whereHas('chapter.course', function ($q) use ($teacherDivisions, $teacherStages, $teacherGrades) {
            $q->wherein('teacher_id', $this->id);

        })->count();

//        return Lesson::whereHas('chapter.course', function ($q) use ($teacherDivisions, $teacherStages, $teacherGrades) {
//            $q->wherein('stage_id', $teacherStages)->whereIn('grade_id', $teacherGrades);
//            if($teacherDivisions){
//                $q->whereIn('division_id', $teacherDivisions);
//            }
//        })->count();
    }

    public function getStudentsCountAttribute()
    {
        $teacherStages = $this->courses->pluck('stage_id');
        $teacherGrades = $this->courses->pluck('grade_id');
        $teacherDivisions = $this->courses->pluck('division_id');

        return Student::where(function ($q) use ($teacherDivisions, $teacherStages, $teacherGrades) {
            $q->whereIn('stage_id', $teacherStages)->whereIn('grade_id', $teacherGrades)
                ->whereIn('division_id', $teacherDivisions);
        })
            ->count();
    }

    public function attachments()
    {
        $attachments = LessonAttachment::query()->whereHas('lesson.chapter.course', function ($query) {
            $query->whereIn('teacher_id', [$this->id]);
        })->latest();

        return $attachments;
    }

    public function stories()
    {
        return $this->hasMany(Story::class);
    }

    public static function getStoriesForStudent(Student $student)
    {
        $teacherIds = self::forStudent($student)->pluck('id');

        return Story::whereIn('teacher_id', $teacherIds)
            ->where('created_at', '>=', now()->subDay())
            ->with('teacher')
            ->get();
    }
}
