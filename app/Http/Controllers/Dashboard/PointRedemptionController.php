<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PointRedemption;
use Illuminate\Http\Request;

class PointRedemptionController extends Controller
{
    public function index()
    {
        $redemptions = PointRedemption::orderBy('points_required')->paginate(15);

        return view('dashboard.point-redemptions.index', compact('redemptions'));
    }

    public function create()
    {
        return view('dashboard.point-redemptions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'points_required' => 'required|integer|min:1',
            'wallet_amount' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        PointRedemption::create($data);

        return redirect()->route('point-redemptions.index')->with('success', 'Redemption rule created successfully.');
    }

    public function edit(PointRedemption $pointRedemption)
    {
        return view('dashboard.point-redemptions.edit', compact('pointRedemption'));
    }

    public function update(Request $request, PointRedemption $pointRedemption)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'points_required' => 'required|integer|min:1',
            'wallet_amount' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $pointRedemption->update($data);

        return redirect()->route('point-redemptions.index')->with('success', 'Redemption rule updated successfully.');
    }

    public function destroy(PointRedemption $pointRedemption)
    {
        $pointRedemption->delete();

        return redirect()->route('point-redemptions.index')->with('success', 'Redemption rule deleted successfully.');
    }
}
