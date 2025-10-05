<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LessonAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'name',
        'path',
        'type',
        'is_featured',
        'bio',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function getFileAttribute()
    {
        if ($this->attributes['path']) {
            // Check if it's a full URL (for external images)
            if (filter_var($this->attributes['path'], FILTER_VALIDATE_URL)) {
                return $this->attributes['path'];
            }

            // Check if file exists in storage
            if (Storage::disk('public')->exists($this->attributes['path'])) {
                return asset(Storage::url($this->attributes['path']));
            }
        }

        // Return default avatar if no image
        return null;
    }

    public function setFileAttribute($value)
    {
        // If value is null or empty, keep existing image
        if (empty($value)) {
            return;
        }

        // If it's an uploaded file
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($this->attributes['path'] ?? null) {
                Storage::disk('public')->delete($this->attributes['path']);
            }

            // Store new image
            $path = $value->store('lesson_attachments', 'public');
            $this->attributes['path'] = $path;
            $this->attributes['type'] = $value->getClientMimeType();
        }
        // If it's a string path
        elseif (is_string($value)) {
            $this->attributes['path'] = $value;
        }
    }
}
