<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\GradeRequest;
use App\Models\Grade;
use App\Models\Stage;
use App\Services\GradeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class GradeController extends Controller
{
    protected GradeService $gradeService;

    public function __construct(GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $grades = $this->gradeService->getAllPaginated();

        return view('dashboard.grades.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $stages = Stage::all();

        return view('dashboard.grades.create', compact('stages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GradeRequest $request): RedirectResponse
    {
        try {
            $this->gradeService->create($request->validated());

            return redirect()->route('grades.index')
                ->with('success', 'Grade created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Grade: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade): View
    {
        return view('dashboard.grades.show', compact('grade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade): View
    {
        $stages = Stage::all();

        return view('dashboard.grades.edit', compact('grade', 'stages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GradeRequest $request, Grade $grade): RedirectResponse
    {
        try {
            $this->gradeService->update($grade, $request->validated());

            return redirect()->route('grades.index')
                ->with('success', 'Grade updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Grade: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade): RedirectResponse
    {
        try {
            $this->gradeService->delete($grade);

            return redirect()->route('grades.index')
                ->with('success', 'Grade deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Grade: '.$e->getMessage());
        }
    }

    /**
     * Return grades for a specific stage (JSON).
     */
    public function byStage(Stage $stage): JsonResponse
    {
        $grades = $stage->grades()->select('id', 'name')->get();

        return response()->json([
            'data' => $grades,
        ]);
    }
}
