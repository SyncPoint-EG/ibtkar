<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Question extends Model
{

    public $with = ['options'];
    protected $fillable = [
        'exam_id',
        'question_text',
        'question_type',
        'marks',
        'order',
        'image',
        'correct_essay_answer'
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
            $path = $value->store('questions/images', 'public');
            $this->attributes['image'] = $path;
        }
        // If it's a string path
        else if (is_string($value)) {
            $this->attributes['image'] = $value;
        }
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }

    public function getCorrectAnswer()
    {
        if ($this->question_type === 'multiple_choice') {
            return $this->options()->where('is_correct', true)->first();
        }
        return null;
    }
}
