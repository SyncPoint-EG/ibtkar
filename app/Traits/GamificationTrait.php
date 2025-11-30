<?php

namespace App\Traits;

use App\Models\ActionPoint;
use App\Models\StudentPointLog;

trait GamificationTrait
{
    public function givePoints($student, $action_name)
    {
        $action = ActionPoint::where('action_name', $action_name)->first();
        $points = $action->points ?? 0;
        $student->points = $student->points + $points;
        $student->save();

        StudentPointLog::create([
            'student_id' => $student->id,
            'action_name' => $action_name,
            'points' => $points,
        ]);

        return $points;
    }

    public function deductPoints($student, $points)
    {
        if ($student->points < $points) {
            return false;
        }

        $student->points -= $points;
        $student->save();

        return true;
    }
}
