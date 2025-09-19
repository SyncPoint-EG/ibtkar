<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'fcm_token',
    ];

    public function user()
    {
        return $this->morphTo();
    }
}
