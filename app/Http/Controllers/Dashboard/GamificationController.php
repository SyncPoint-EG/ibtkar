<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActionPoint;
use App\Models\RewardPoint;
use App\Models\LuckWheelItem;
use Illuminate\Http\Request;

class GamificationController extends Controller
{
    public function editActionPoints()
    {
        $actionPoints = ActionPoint::all();
        return view('dashboard.action-points', compact('actionPoints'));
    }

    public function updateActionPoints(Request $request)
    {
        $request->validate([
            'points' => 'required|array',
            'points.*' => 'required|integer|min:0',
        ]);

        foreach ($request->points as $id => $points) {
            ActionPoint::where('id', $id)->update(['points' => $points]);
        }

        return redirect()->route('action-points.edit')
            ->with('success', 'Action points updated successfully.');
    }

    public function editRewardPoints()
    {
        $rewardPoints = RewardPoint::all();
        return view('dashboard.reward-points', compact('rewardPoints'));
    }

    public function updateRewardPoints(Request $request)
    {
        $request->validate([
            'points_cost' => 'required|array',
            'points_cost.*' => 'required|integer|min:0',
        ]);

        foreach ($request->points_cost as $id => $points_cost) {
            RewardPoint::where('id', $id)->update(['points_cost' => $points_cost]);
        }

        return redirect()->route('reward-points.edit')
            ->with('success', 'Reward points updated successfully.');
    }

    public function editLuckWheelItems()
    {
        $luckWheelItems = LuckWheelItem::all();
        return view('dashboard.luck-wheel', compact('luckWheelItems'));
    }

    public function updateLuckWheelItems(Request $request)
    {
        $request->validate([
            'appearance_percentage' => 'required|array',
            'appearance_percentage.*' => 'required|integer|min:0',
        ]);

        if (array_sum($request->appearance_percentage) > 100) {
            return redirect()->route('luck-wheel.edit')
                ->with('error', trans('dashboard.luck_wheel.update_error'));
        }

        foreach ($request->appearance_percentage as $id => $percentage) {
            LuckWheelItem::where('id', $id)->update(['appearance_percentage' => $percentage]);
        }

        return redirect()->route('luck-wheel.edit')
            ->with('success', trans('dashboard.luck_wheel.update_success'));
    }
}
