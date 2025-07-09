<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\GradeRequest;
use App\Models\Stage;
use App\Services\GradeService;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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
     *
     * @return View
     */
    public function index(): View
    {
        $grades = $this->gradeService->getAllPaginated();

        return view('dashboard.grades.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $stages = Stage::all();
        return view('dashboard.grades.create',compact('stages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GradeRequest $request
     * @return RedirectResponse
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
                ->with('error', 'Error creating Grade: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Grade $grade
     * @return View
     */
    public function show(Grade $grade): View
    {
        return view('dashboard.grades.show', compact('grade'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Grade $grade
     * @return View
     */
    public function edit(Grade $grade): View
    {
        $stages = Stage::all();
        return view('dashboard.grades.edit', compact('grade','stages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GradeRequest $request
     * @param Grade $grade
     * @return RedirectResponse
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
                ->with('error', 'Error updating Grade: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Grade $grade
     * @return RedirectResponse
     */
    public function destroy(Grade $grade): RedirectResponse
    {
        try {
            $this->gradeService->delete($grade);

            return redirect()->route('grades.index')
                ->with('success', 'Grade deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Grade: ' . $e->getMessage());
        }
    }
}
