<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'content',
        'description',
        'type',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function getContentAttribute()
    {
        if ($this->attributes['content']) {
            // Check if it's a full URL (for external images)
            if (filter_var($this->attributes['content'], FILTER_VALIDATE_URL)) {
                return $this->attributes['content'];
            }

            // Check if file exists in storage
            if (Storage::disk('public')->exists($this->attributes['content'])) {
                return asset(Storage::url($this->attributes['content']));
            }
        }

        // Return default avatar if no image
        return asset('dashboard/app-assets/images/portrait/small/avatar-s-1.png');
    }

    /**
     * Set the user's image.
     * This is a setter that handles image upload
     */
    public function setContentAttribute($value)
    {
        // If value is null or empty, keep existing image
        if (empty($value)) {
            return;
        }

        // If it's an uploaded file
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($this->attributes['content'] ?? null) {
                Storage::disk('public')->delete($this->attributes['content']);
            }

            // Store new image
            $path = $value->store('stories', 'public');
            $this->attributes['content'] = $path;
        }
        // If it's a string path
        elseif (is_string($value)) {
            $this->attributes['content'] = $value;
        }
    }

    public function scopeFresh($query)
    {
        return $query->where('created_at', ' > ', now()->subDay());
    }
}
