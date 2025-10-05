<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'for', 'number_of_uses', 'expires_at', 'teacher_id', 'code_classification', 'price'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->attributes['name_'.app()->getLocale()];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'payment_code', 'code');
    }
}
