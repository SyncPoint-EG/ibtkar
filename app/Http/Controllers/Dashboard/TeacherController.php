<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRequest;
use App\Models\Division;
use App\Models\Grade;
use App\Models\Stage;
use App\Models\Subject;
use App\Services\TeacherService;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TeacherController extends Controller
{
    protected TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $teachers = $this->teacherService->getAllPaginated();

        return view('dashboard.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $subjects = Subject::all();
        $stages = Stage::all();
        $grades = Grade::all();
        $divisions = Division::all();

        return view('dashboard.teachers.create', compact('subjects', 'stages', 'grades', 'divisions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TeacherRequest $request
     * @return RedirectResponse
     */
    public function store(TeacherRequest $request): RedirectResponse
    {
        try {
            $teacher  = $this->teacherService->create($request->validated());
// Sync many-to-many relationships
            if ($request->has('subjects')) {
                $teacher->subjects()->sync($request->subjects);
            }

            if ($request->has('stages')) {
                $teacher->stages()->sync($request->stages);
            }

            if ($request->has('grades')) {
                $teacher->grades()->sync($request->grades);
            }

            if ($request->has('divisions')) {
                $teacher->divisions()->sync($request->divisions);
            }

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Teacher: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Teacher $teacher
     * @return View
     */
    public function show(Teacher $teacher): View
    {
        return view('dashboard.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Teacher $teacher
     * @return View
     */
    public function edit(Teacher $teacher): View
    {
        $subjects = Subject::all();
        $stages = Stage::all();
        $grades = Grade::all();
        $divisions = Division::all();

        return view('dashboard.teachers.edit', compact('teacher','subjects', 'stages', 'grades', 'divisions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TeacherRequest $request
     * @param Teacher $teacher
     * @return RedirectResponse
     */
    public function update(TeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        try {
            $this->teacherService->update($teacher, $request->validated());
            // Sync many-to-many relationships
            $teacher->subjects()->sync($request->subjects ?? []);
            $teacher->stages()->sync($request->stages ?? []);
            $teacher->grades()->sync($request->grades ?? []);
            $teacher->divisions()->sync($request->divisions ?? []);
            return redirect()->route('teachers.index')
                ->with('success', 'Teacher updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Teacher: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Teacher $teacher
     * @return RedirectResponse
     */
    public function destroy(Teacher $teacher): RedirectResponse
    {
        try {
            $this->teacherService->delete($teacher);

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Teacher: ' . $e->getMessage());
        }
    }
}
