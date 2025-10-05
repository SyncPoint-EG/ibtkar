<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'logo', 'second_logo', 'uuid'];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */

    //     public function getNameAttribute()
    //     {
    //         return $this->attributes['name_'.app()->getLocale()];
    //     }
    public static function boot()
    {
        parent::boot();

        static::creating(function ($subject) {
            do {
                $code = strtoupper(Str::random(8));
            } while (self::where('uuid', $code)->exists());

            $subject->uuid = $code;
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function getLogoAttribute()
    {
        if ($this->attributes['logo']) {
            // Check if it's a full URL (for external images)
            if (filter_var($this->attributes['logo'], FILTER_VALIDATE_URL)) {
                return $this->attributes['logo'];
            }

            // Check if file exists in storage
            if (Storage::disk('public')->exists($this->attributes['logo'])) {
                return asset(Storage::url($this->attributes['logo']));
            }
        }

        // Return default avatar if no image
        return asset('dashboard/app-assets/images/portrait/small/avatar-s-1.png');
    }

    /**
     * Set the user's image.
     * This is a setter that handles image upload
     */
    public function setLogoAttribute($value)
    {
        // If value is null or empty, keep existing image
        if (empty($value)) {
            return;
        }

        // If it's an uploaded file
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($this->attributes['logo'] ?? null) {
                Storage::disk('public')->delete($this->attributes['logo']);
            }

            // Store new image
            $path = $value->store('subjects/logos', 'public');
            $this->attributes['logo'] = $path;
        }
        // If it's a string path
        elseif (is_string($value)) {
            $this->attributes['logo'] = $value;
        }
    }

    public function getSecondLogoAttribute()
    {
        if ($this->attributes['second_logo']) {
            // Check if it's a full URL (for external images)
            if (filter_var($this->attributes['second_logo'], FILTER_VALIDATE_URL)) {
                return $this->attributes['second_logo'];
            }

            // Check if file exists in storage
            if (Storage::disk('public')->exists($this->attributes['second_logo'])) {
                return asset(Storage::url($this->attributes['second_logo']));
            }
        }

        // Return default avatar if no image
        return asset('dashboard/app-assets/images/portrait/small/avatar-s-1.png');
    }

    /**
     * Set the user's image.
     * This is a setter that handles image upload
     */
    public function setSecondLogoAttribute($value)
    {
        // If value is null or empty, keep existing image
        if (empty($value)) {
            return;
        }

        // If it's an uploaded file
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($this->attributes['second_logo'] ?? null) {
                Storage::disk('public')->delete($this->attributes['second_logo']);
            }

            // Store new image
            $path = $value->store('subjects/logos', 'public');
            $this->attributes['second_logo'] = $path;
        }
        // If it's a string path
        elseif (is_string($value)) {
            $this->attributes['second_logo'] = $value;
        }
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'subject_id');
    }

    public function subjectTeacherAssignments()
    {
        return $this->hasMany(SubjectTeacher::class, 'subject_id')->with(['subject', 'grade', 'stage', 'division']);
    }
}
