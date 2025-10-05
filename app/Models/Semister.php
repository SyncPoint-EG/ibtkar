<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semister extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'start_month', 'start_day', 'end_month', 'end_day'];

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
}
