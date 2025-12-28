<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradePlan extends Model
{
    use HasFactory;

    public const TYPE_GENERAL = 'general';

    protected $fillable = [
        'stage_id',
        'grade_id',
        'general_plan_price',
        'is_active',
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function planTypes(): array
    {
        return [
            self::TYPE_GENERAL,
        ];
    }
}
