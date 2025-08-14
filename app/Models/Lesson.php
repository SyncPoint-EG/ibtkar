<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'desc', 'video_link', 'video_image', 'chapter_id', 'price', 'is_featured', 'type'];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */


     public function scopeActive($query)
     {
         return $query->where('is_active', 1);
     }


    public function chapter()
    {
        return $this->belongsTo(\App\Models\Chapter::class);
    }

    public function attachments()
    {
        return $this->hasMany(LessonAttachment::class);
    }

    public function getVideoImageAttribute()
    {
        if ($this->attributes['video_image']) {
            // Check if it's a full URL (for external images)
            if (filter_var($this->attributes['video_image'], FILTER_VALIDATE_URL)) {
                return $this->attributes['video_image'];
            }

            // Check if file exists in storage
            if (Storage::disk('public')->exists($this->attributes['video_image'])) {
                return asset(Storage::url($this->attributes['video_image']));
            }
        }

        // Return default avatar if no image
        return asset('dashboard/app-assets/images/portrait/small/avatar-s-1.png');
    }

    /**
     * Set the user's image.
     * This is a setter that handles image upload
     */
    public function setVideoImageAttribute($value)
    {
        // If value is null or empty, keep existing image
        if (empty($value)) {
            return;
        }

        // If it's an uploaded file
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($this->attributes['video_image'] ?? null) {
                Storage::disk('public')->delete($this->attributes['video_image']);
            }

            // Store new image
            $path = $value->store('users/avatars', 'public');
            $this->attributes['video_image'] = $path;
        }
        // If it's a string path
        else if (is_string($value)) {
            $this->attributes['video_image'] = $value;
        }
    }
}
