<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\LuckWheelResource;
use App\Models\LuckWheelItem;
use App\Models\StudentLuckWheelSpin;
use App\Traits\GamificationTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LuckWheelController extends Controller
{
    use GamificationTrait;

    public function index()
    {
        $items = LuckWheelItem::all();

        return LuckWheelResource::collection($items);
    }

    public function spin(Request $request)
    {
        $student = auth('student')->user();
        $request->validate([
            'luck_wheel_item_id' => 'required|exists:luck_wheel_items,id',
        ]);

        // Check if the student has already spun the wheel today
        $lastSpin = StudentLuckWheelSpin::where('student_id', $student->id)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if ($lastSpin) {
            return response()->json(['message' => 'You have already spun the wheel today.'], 400);
        }

//        $items = LuckWheelItem::all();
//        $winningItem = $this->getWinningItem($items);

        // Award points to the student
        // $this->givePoints($student, 'Luck Wheel Spin');

        $winningItem = LuckWheelItem::findOrFail($request->luck_wheel_item_id);
        // Record the spin
        StudentLuckWheelSpin::create([
            'student_id' => $student->id,
            'luck_wheel_item_id' => $winningItem->id,
        ]);

        return new LuckWheelResource($winningItem);
    }

    private function getWinningItem($items)
    {
        $totalPercentage = $items->sum('appearance_percentage');
        $randomNumber = mt_rand(1, $totalPercentage);

        $cumulativePercentage = 0;
        foreach ($items as $item) {
            $cumulativePercentage += $item->appearance_percentage;
            if ($randomNumber <= $cumulativePercentage) {
                return $item;
            }
        }

        return $items->first();
    }

    public function checkSpin()
    {
        $student = auth('student')->user();
        $lastSpin = StudentLuckWheelSpin::where('student_id', $student->id)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if ($lastSpin) {
            return response()->json(['message' => 'You have already spun the wheel today.'], 400);
        }else{
            return response()->json(['message' => 'You can spin the wheel today.'], 200);
        }
    }
}
