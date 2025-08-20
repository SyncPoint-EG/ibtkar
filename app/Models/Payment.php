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
        else if (is_string($value)) {
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
}
