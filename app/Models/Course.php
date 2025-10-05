<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'year', 'teacher_id', 'education_type_id', 'stage_id', 'grade_id', 'division_id', 'semister_id', 'subject_id', 'price', 'is_featured', 'bio', 'website_image'];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
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
            $path = $value->store('courses/images', 'public');
            $this->attributes['website_image'] = $path;
        }
        // If it's a string path
        elseif (is_string($value)) {
            $this->attributes['website_image'] = $value;
        }
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

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
