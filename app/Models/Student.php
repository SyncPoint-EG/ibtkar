<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'phone', 'password', 'governorate_id', 'district_id', 'center_id', 'stage_id', 'grade_id', 'division_id', 'gender', 'birth_date', 'status','verification_code'];

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

     public function getNameAttribute()
     {
         return $this->attributes['name_'.app()->getLocale()];
     }

     public function scopeActive($query)
     {
         return $query->where('is_active', 1);
     }


    public function governorate()
    {
        return $this->belongsTo(\App\Models\Governorate::class);
    }

    public function district()
    {
        return $this->belongsTo(\App\Models\District::class);
    }

    public function center()
    {
        return $this->belongsTo(\App\Models\Center::class);
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

    public function generateVerificationCode()
    {
        $code = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        $this->update([
            'verification_code' => $code,
//            'verification_code_expires_at' => now()->addMinutes(15), // Code expires in 15 minutes
        ]);

        return $code;
    }

}
