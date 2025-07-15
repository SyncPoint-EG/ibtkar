<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'phone', 'other_phone', 'bio', 'image', 'rate'];

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


    public function subjects()
    {
        return $this->belongsToMany(\App\Models\Subject::class);
    }

    public function stages()
    {
        return $this->belongsToMany(\App\Models\Stage::class);
    }

    public function grades()
    {
        return $this->belongsToMany(\App\Models\Grade::class);
    }

    public function divisions()
    {
        return $this->belongsToMany(\App\Models\Division::class);
    }
}
