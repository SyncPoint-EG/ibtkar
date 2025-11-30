<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointRedemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'points_required',
        'wallet_amount',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
