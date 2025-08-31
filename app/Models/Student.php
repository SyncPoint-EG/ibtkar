<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Lesson;
use App\Models\Chapter;
use App\Models\Payment;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'phone', 'password', 'governorate_id', 'district_id', 'center_id', 'stage_id', 'grade_id', 'division_id', 'gender', 'birth_date', 'status','verification_code','mac_address','referral_code'];

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
                    'lesson'
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

        if (!$lesson) {
            return false;
        }

        // Check for lesson purchase
        $isPurchased = Payment::where('student_id', $this->id)
            ->where('lesson_id', $lessonId)->where('payment_status',\App\Models\Payment::PAYMENT_STATUS['approved'])
            ->exists();

        if ($isPurchased) {
            return true;
        }

        // Check for chapter purchase
        $isPurchased = Payment::where('student_id', $this->id)
            ->where('chapter_id', $lesson->chapter_id)->where('payment_status',\App\Models\Payment::PAYMENT_STATUS['approved'])
            ->exists();

        if ($isPurchased) {
            return true;
        }

        // Check for course purchase
        $chapter = Chapter::find($lesson->chapter_id);

        if ($chapter) {
            $isPurchased = Payment::where('student_id', $this->id)
                ->where('course_id', $chapter->course_id)->where('payment_status',\App\Models\Payment::PAYMENT_STATUS['approved'])
                ->exists();
        }

        return $isPurchased;
    }

    public function isEnrolledInCourse($courseId)
    {
        if (!$courseId) {
            return false;
        }

        return Payment::where('student_id', $this->id)
            ->where('course_id', $courseId)
            ->where('payment_status', Payment::PAYMENT_STATUS['approved'])
            ->exists();
    }

    public function favorites()
    {
        return $this->belongsToMany(Lesson::class, 'favorites', 'student_id', 'lesson_id');
    }

}
