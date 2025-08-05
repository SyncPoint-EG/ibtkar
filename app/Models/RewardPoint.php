<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RewardPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'reward_name',
        'points_cost',
        'description',
    ];
}
