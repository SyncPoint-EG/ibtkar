<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActionPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_name',
        'points',
        'description',
    ];
}
