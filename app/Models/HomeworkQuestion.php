<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomeworkQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'homework_id',
        'question_text',
        'question_type',
        'marks',
        'order',
        'is_required',
        'image',
    ];

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
        return null;
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
            $path = $value->store('homework_questions/images', 'public');
            $this->attributes['image'] = $path;
        }
        // If it's a string path
        elseif (is_string($value)) {
            $this->attributes['image'] = $value;
        }
    }

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }

    public function options()
    {
        return $this->hasMany(HomeworkQuestionOption::class)->orderBy('order');
    }

    public function answers()
    {
        return $this->hasMany(HomeworkAnswer::class);
    }

    public function getCorrectOption()
    {
        return $this->options()->where('is_correct', true)->first();
    }
}
