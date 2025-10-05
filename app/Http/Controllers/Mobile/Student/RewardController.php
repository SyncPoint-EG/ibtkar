<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Models\RewardPoint;
use App\Models\StudentReward;
use App\Traits\GamificationTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    use GamificationTrait;

    public function index(): JsonResponse
    {
        $rewards = RewardPoint::all();

        return response()->json(['data' => $rewards]);
    }

    public function purchase($reward_pointId): JsonResponse
    {
        $student = Auth::guard('student')->user();
        $reward_point = RewardPoint::findOrFail($reward_pointId);
        if ($this->deductPoints($student, $reward_point->points_cost)) {
            StudentReward::create([
                'student_id' => $student->id,
                'reward_point_id' => $reward_point->id,
            ]);

            return response()->json(['message' => 'Reward purchased successfully.']);
        }

        return response()->json(['message' => 'Not enough points.'], 422);
    }
    public function rewardsHistory()
    {
        $student = Auth::guard('student')->user();

        $rewardsHistory = StudentReward::with('rewardPoint')
            ->where('student_id', $student->id)
            ->get();
        return response()->json(['data' => $rewardsHistory]);
    }
}
