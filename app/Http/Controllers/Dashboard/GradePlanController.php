<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\GradePlanRequest;
use App\Models\Grade;
use App\Models\GradePlan;
use App\Models\Stage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GradePlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $gradePlans = GradePlan::with(['stage', 'grade'])->paginate(15);

        return view('dashboard.grade_plans.index', compact('gradePlans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $stages = Stage::all();
        $grades = Grade::all();

        return view('dashboard.grade_plans.create', compact('stages', 'grades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GradePlanRequest $request): RedirectResponse
    {
        try {
            $payload = $request->validated();
            $payload['is_active'] = $payload['is_active'] ?? true;
            GradePlan::create($payload);

            return redirect()->route('grade-plans.index')
                ->with('success', __('dashboard.grade_plan.created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('dashboard.grade_plan.create_error') . ' ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GradePlan $grade_plan): View
    {
        $stages = Stage::all();
        $grades = Grade::all();

        return view('dashboard.grade_plans.edit', [
            'gradePlan' => $grade_plan,
            'stages' => $stages,
            'grades' => $grades,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GradePlanRequest $request, GradePlan $grade_plan): RedirectResponse
    {
        try {
            $payload = $request->validated();
            $payload['is_active'] = $payload['is_active'] ?? false;
            $grade_plan->update($payload);

            return redirect()->route('grade-plans.index')
                ->with('success', __('dashboard.grade_plan.updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('dashboard.grade_plan.update_error') . ' ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GradePlan $grade_plan): RedirectResponse
    {
        try {
            $grade_plan->delete();

            return redirect()->route('grade-plans.index')
                ->with('success', __('dashboard.grade_plan.deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('dashboard.grade_plan.delete_error') . ' ' . $e->getMessage());
        }
    }
}
