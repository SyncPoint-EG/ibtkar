<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRequest;
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
    public function create(): View
    {
        return view('dashboard.teachers.create');
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
            $this->teacherService->create($request->validated());

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
        return view('dashboard.teachers.edit', compact('teacher'));
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
