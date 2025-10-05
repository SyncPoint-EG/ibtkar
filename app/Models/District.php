<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class District extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'governorate_id'];

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

    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }
}
