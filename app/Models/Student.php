<?php

namespace App\Models;

use App\Models\StudentPointLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory ,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'phone', 'password', 'governorate_id', 'district_id', 'center_id', 'stage_id', 'grade_id', 'division_id', 'gender', 'birth_date', 'status', 'verification_code', 'mac_address', 'referral_code', 'image','wallet','education_type_id'];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            do {
                $code = strtoupper(Str::random(8));
            } while (self::where('referral_code', $code)->exists());

            $student->referral_code = $code;
        });
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getImageAttribute()
    {
        if (isset($this->attributes['image']) && $this->attributes['image']) {
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
            $path = $value->store('students/avatars', 'public');
            $this->attributes['image'] = $path;
        }
        // If it's a string path
        elseif (is_string($value)) {
            $this->attributes['image'] = $value;
        }
    }

    public function referrals()
    {
        return $this->hasMany(Student::class, 'referred_by');
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'birth_date' => 'date',
        ];
    }

    //     public function getNameAttribute()
    //     {
    //         return $this->attributes['name_'.app()->getLocale()];
    //     }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function governorate()
    {
        return $this->belongsTo(\App\Models\Governorate::class);
    }

    public function district()
    {
        return $this->belongsTo(\App\Models\District::class);
    }

    public function center()
    {
        return $this->belongsTo(\App\Models\Center::class);
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

    public function educationType()
    {
        return $this->belongsTo(\App\Models\EducationType::class);
    }

    public function generateVerificationCode()
    {
        $code = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        $this->update([
            'verification_code' => $code,
            //            'verification_code_expires_at' => now()->addMinutes(15), // Code expires in 15 minutes
        ]);

        return $code;
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    public function pointLogs()
    {
        return $this->hasMany(StudentPointLog::class);
    }

    public function purchases()
    {
        return $this->hasMany(Payment::class);
    }

    protected function purchasedLessonsCount(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function () {
                $totalLessons = 0;

                // Eager load relationships to avoid N+1 problem
                $payments = $this->payments()->with([
                    'course.chapters.lessons',
                    'chapter.lessons',
                    'lesson',
                ])->get();

                foreach ($payments as $payment) {
                    if ($payment->lesson_id) {
                        // Purchase of a single lesson
                        $totalLessons++;
                    } elseif ($payment->chapter_id && $payment->chapter) {
                        // Purchase of a chapter, count its lessons
                        $totalLessons += $payment->chapter->lessons->count();
                    } elseif ($payment->course_id && $payment->course) {
                        // Purchase of a course, count lessons in all its chapters
                        foreach ($payment->course->chapters as $chapter) {
                            $totalLessons += $chapter->lessons->count();
                        }
                    }
                }

                return $totalLessons;
            }
        );
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    public function watches()
    {
        return $this->hasMany(Watch::class);
    }

    public function isLessonPurchased($lessonId)
    {
        $lesson = Lesson::find($lessonId);

        if (! $lesson) {
            return false;
        }

        if ($lesson->price == 0 || $lesson->chapter->price == 0 || $lesson->chapter->course->price == 0) {
            return true;
        }
        // Check for lesson purchase
        $isPurchased = Payment::where('student_id', $this->id)
            ->where('lesson_id', $lessonId)->where('payment_status', \App\Models\Payment::PAYMENT_STATUS['approved'])->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereIn('payment_method', ['instapay', 'wallet'])
                        ->where('payment_status', Payment::PAYMENT_STATUS['approved']);
                })->orWhereNotIn('payment_method', ['instapay', 'wallet']);
            })
            ->exists();

        if ($isPurchased) {
            return true;
        }

        // Check for chapter purchase
        $isPurchased = Payment::where('student_id', $this->id)
            ->where('chapter_id', $lesson->chapter_id)->where('payment_status', \App\Models\Payment::PAYMENT_STATUS['approved'])->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereIn('payment_method', ['instapay', 'wallet'])
                        ->where('payment_status', Payment::PAYMENT_STATUS['approved']);
                })->orWhereNotIn('payment_method', ['instapay', 'wallet']);
            })
            ->exists();

        if ($isPurchased) {
            return true;
        }

        // Check for course purchase
        $chapter = Chapter::find($lesson->chapter_id);

        if ($chapter) {
            $isPurchased = Payment::where('student_id', $this->id)
                ->where('course_id', $chapter->course_id)->where('payment_status', \App\Models\Payment::PAYMENT_STATUS['approved'])->where(function ($query) {
                    $query->where(function ($q) {
                        $q->whereIn('payment_method', ['instapay', 'wallet'])
                            ->where('payment_status', Payment::PAYMENT_STATUS['approved']);
                    })->orWhereNotIn('payment_method', ['instapay', 'wallet']);
                })
                ->exists();
        }

        return $isPurchased;
    }

    public function isEnrolledInCourse($courseId)
    {
        if (! $courseId) {
            return false;
        }

        return Payment::where('student_id', $this->id)
            ->where('course_id', $courseId)
            ->where('payment_status', Payment::PAYMENT_STATUS['approved'])
            ->exists();
    }

    public function isEnrolledInLesson(Lesson $lesson)
    {
        if (! $lesson) {
            return false;
        }

        return Payment::where('student_id', $this->id)
            ->where(function ($query) use ($lesson) {
                $query->where('lesson_id', $lesson->id)
                    ->orWhere('chapter_id', $lesson->chapter_id)
                    ->orWhere('course_id', $lesson->chapter->course_id);
            })
            ->where('payment_status', Payment::PAYMENT_STATUS['approved'])
            ->exists();
    }

    public function favorites()
    {
        return $this->belongsToMany(Lesson::class, 'favorites', 'student_id', 'lesson_id');
    }

    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function getSubjectExamAverages($month = null)
    {
        $subjectAverages = [];

        // Get all subjects for the student's stage, grade, and division
        $courseIds = Course::where('stage_id', $this->stage_id)
            ->where('grade_id', $this->grade_id)
            ->where('division_id', $this->division_id)
            ->pluck('id');

        $subjects = Subject::whereIn('id', Course::whereIn('id', $courseIds)->pluck('subject_id'))->get();

        foreach ($subjects as $subject) {
            // Get all exams for the courses of this subject
            $examIdsQuery = Exam::whereHas('lesson.chapter.course', function ($query) use ($subject, $courseIds) {
                $query->where('subject_id', $subject->id)->whereIn('id', $courseIds);
            });

            if ($month) {
                $examIdsQuery->whereMonth('created_at', $month);
            }

            $examIds = $examIdsQuery->pluck('id');

            // Get all exam attempts for the student for these exams
            $attempts = ExamAttempt::where('student_id', $this->id)
                ->whereIn('exam_id', $examIds)
                ->get();

            if ($attempts->isEmpty()) {
                $subjectAverages[$subject->name] = [
                    'average' => 0,
                    'exams_count' => 0,
                    'exams_degrees' => [],
                ];

                continue;
            }

            // Calculate the average score
            $totalScore = $attempts->sum('score');
            $totalMarks = $attempts->sum('total_marks');
            $average = $totalMarks > 0 ? ($totalScore / $totalMarks) * 100 : 0;

            $examsDegrees = [];
            $exams = $examIdsQuery->get();
            foreach ($exams as $exam) {
                $latestAttempt = $this->examAttempts()->where('exam_id', $exam->id)->latest()->first();
                if ($latestAttempt) {
                    $examsDegrees[] = [
                        'exam_name' => $exam->title,
                        'degree' => $latestAttempt->score,
                        'total_marks' => $latestAttempt->total_marks,
                    ];
                }
            }

            // Add to the list
            $subjectAverages[$subject->name] = [
                'average' => round($average, 2),
                'exams_count' => $examIdsQuery->count(),
                'exams_degrees' => $examsDegrees,
            ];
        }

        return $subjectAverages;
    }

    public function getLessonAttendancePercentage($month = null)
    {
        $lessonAttendance = [];

        // Get all subjects for the student's stage, grade , and division
        $courseIds = Course::where('stage_id', $this->stage_id)
            ->where('grade_id', $this->grade_id)
            ->where('division_id', $this->division_id)
            ->pluck('id');
        $subjects = Subject::whereIn('id', Course::whereIn('id', $courseIds)->pluck('subject_id'))->get();

        foreach ($subjects as $subject) {
            // Get all lessons for the subject
            $lessonsQuery = Lesson::whereHas('chapter.course', function ($query) use ($subject, $courseIds) {
                $query->where('subject_id', $subject->id)->whereIn('id', $courseIds);
            });

            if ($month) {
                $lessonsQuery->whereMonth('created_at', $month);
            }

            $lessonIds = $lessonsQuery->pluck('id');

            if ($lessonIds->isEmpty()) {
                $lessonAttendance[$subject->name] = 0;

                continue;
            }

            // Get all watched lessons for the subject
            $watchedLessonIds = $this->watches()->whereIn('lesson_id', $lessonIds)->pluck('lesson_id')->unique();

            // Calculate the attendance percentage
            $percentage = ($watchedLessonIds->count() / $lessonIds->count()) * 100;

            $lessonAttendance[$subject->name] = round($percentage, 2);
        }

        return $lessonAttendance;
    }

    public function devices()
    {
        return $this->morphMany(UserDevice::class, 'user');
    }

    public function routeNotificationForFcm($notification = null)
    {
        return $this->devices()->pluck('fcm_token')->toArray();
    }

    public function rewards()
    {
        return $this->hasMany(StudentReward::class);
    }
}
