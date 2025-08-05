<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class LuckWheelItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'gift',
        'appearance_percentage',
    ];
}
