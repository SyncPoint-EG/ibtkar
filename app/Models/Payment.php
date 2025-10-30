<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    protected $guarded = [];

    const PAYMENT_STATUS = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ];

    public function getPaymentImageAttribute()
    {
        if ($this->attributes['payment_image']) {
            // Check if it's a full URL (for external images)
            if (filter_var($this->attributes['payment_image'], FILTER_VALIDATE_URL)) {
                return $this->attributes['payment_image'];
            }

            // Check if file exists in storage
            if (Storage::disk('public')->exists($this->attributes['payment_image'])) {
                return asset(Storage::url($this->attributes['payment_image']));
            }
        }

        // Return default avatar if no image
        return asset('dashboard/app-assets/images/portrait/small/avatar-s-1.png');
    }

    /**
     * Set the user's image.
     * This is a setter that handles image upload
     */
    public function setPaymentImageAttribute($value)
    {
        // If value is null or empty, keep existing image
        if (empty($value)) {
            return;
        }

        // If it's an uploaded file
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($this->attributes['payment_image'] ?? null) {
                Storage::disk('public')->delete($this->attributes['payment_image']);
            }

            // Store new image
            $path = $value->store('payments', 'public');
            $this->attributes['payment_image'] = $path;
        }
        // If it's a string path
        elseif (is_string($value)) {
            $this->attributes['payment_image'] = $value;
        }
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (!empty($filters['student_name'])) {
            $studentName = trim($filters['student_name']);
            $query->whereHas('student', function ($studentQuery) use ($studentName) {
                $studentQuery->where(function ($nameQuery) use ($studentName) {
                    $nameQuery->where('first_name', 'like', "%{$studentName}%")
                        ->orWhere('last_name', 'like', "%{$studentName}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$studentName}%"]);
                });
            });
        }

        if (!empty($filters['student_phone'])) {
            $studentPhone = trim($filters['student_phone']);
            $query->whereHas('student', function ($studentQuery) use ($studentPhone) {
                $studentQuery->where('phone', 'like', "%{$studentPhone}%");
            });
        }

        return $query;
    }
}
