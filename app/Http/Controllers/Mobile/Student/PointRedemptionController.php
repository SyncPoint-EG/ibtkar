<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Models\PointRedemption;
use App\Models\StudentPointLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointRedemptionController extends Controller
{
    public function index()
    {
        $redemptions = PointRedemption::where('is_active', true)
            ->orderBy('points_required')
            ->get();

        return response()->json([
            'data' => $redemptions,
        ]);
    }

    public function redeem(Request $request, PointRedemption $pointRedemption)
    {
        $student = $request->user();

        if (! $pointRedemption->is_active) {
            return response()->json(['message' => 'This redemption is inactive'], 400);
        }

        if ($student->points < $pointRedemption->points_required) {
            return response()->json(['message' => 'Not enough points'], 400);
        }

        DB::transaction(function () use ($student, $pointRedemption) {
            $student->points -= $pointRedemption->points_required;
            $student->wallet += $pointRedemption->wallet_amount;
            $student->save();

            StudentPointLog::create([
                'student_id' => $student->id,
                'action_name' => 'redeem:'.$pointRedemption->name,
                'points' => -$pointRedemption->points_required,
            ]);
        });

        return response()->json([
            'message' => 'Redeemed successfully',
            'points' => $student->fresh()->points,
            'wallet' => $student->wallet,
        ]);
    }
}
