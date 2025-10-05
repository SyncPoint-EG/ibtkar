<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Center extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'image'];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */

    //     public function getNameAttribute()
    //     {
    //         return $this->attributes['name_'.app()->getLocale()];
    //     }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

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
        return asset('dashboard/app-assets/images/portrait/small/avatar-s-1.png');
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
            $path = $value->store('users/avatars', 'public');
            $this->attributes['image'] = $path;
        }
        // If it's a string path
        elseif (is_string($value)) {
            $this->attributes['image'] = $value;
        }
    }
}
