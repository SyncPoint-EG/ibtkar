<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Services\StudentService;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentController extends Controller
{
    protected StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $students = $this->studentService->getAllPaginated();

        return view('dashboard.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('dashboard.students.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StudentRequest $request
     * @return RedirectResponse
     */
    public function store(StudentRequest $request): RedirectResponse
    {
        try {
            $this->studentService->create($request->validated());

            return redirect()->route('students.index')
                ->with('success', 'Student created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Student: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Student $student
     * @return View
     */
    public function show(Student $student): View
    {
        return view('dashboard.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Student $student
     * @return View
     */
    public function edit(Student $student): View
    {
        return view('dashboard.students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StudentRequest $request
     * @param Student $student
     * @return RedirectResponse
     */
    public function update(StudentRequest $request, Student $student): RedirectResponse
    {
        try {
            $this->studentService->update($student, $request->validated());

            return redirect()->route('students.index')
                ->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Student: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Student $student
     * @return RedirectResponse
     */
    public function destroy(Student $student): RedirectResponse
    {
        try {
            $this->studentService->delete($student);

            return redirect()->route('students.index')
                ->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Student: ' . $e->getMessage());
        }
    }
}
