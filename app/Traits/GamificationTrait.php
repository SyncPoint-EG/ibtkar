<?php

namespace App\Traits;

use App\Models\ActionPoint;

trait GamificationTrait
{
    public function givePoints($student, $action_name)
    {
        $action = ActionPoint::where('action_name', $action_name)->first();
        $points = $action->points ?? 0;
        $student->points = $student->points + $points;
        $student->save();

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
