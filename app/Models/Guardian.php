<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Guardian extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'phone', 'student_phone', 'password'];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function devices()
    {
        return $this->morphMany(UserDevice::class, 'user');
    }

    public function routeNotificationForFcm($notification = null)
    {
        return $this->devices()->pluck('fcm_token')->toArray();
    }

    public function getNameAttribute()
    {
        return $this->attributes['name_'.app()->getLocale()];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function generateVerificationCode()
    {
        $code = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        $this->update([
            'verification_code' => $code,
            //            'verification_code_expires_at' => now()->addMinutes(15), // Code expires in 15 minutes
        ]);

        return $code;
    }

    public function children()
    {
        return $this->hasMany(Student::class, 'guardian_id', 'id');
    }
}
