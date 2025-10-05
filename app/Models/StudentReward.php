<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'reward_point_id',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function rewardPoint()
    {
        return $this->belongsTo(RewardPoint::class);
    }
}
