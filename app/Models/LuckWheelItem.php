<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuckWheelItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'gift',
        'appearance_percentage',
    ];
}
