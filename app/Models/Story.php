<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'content',
        'description',
        'type',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function getContentAttribute($value)
    {
        return Storage::disk('stories')->url($value);
    }

    public function setContentAttribute($value)
    {
        if (is_file($value)) {
            $this->attributes['content'] = $value->store('', 'stories');
        }
    }

    public function scopeFresh($query)
    {
        return $query->where('created_at', ' > ', now()->subDay());
    }
}
