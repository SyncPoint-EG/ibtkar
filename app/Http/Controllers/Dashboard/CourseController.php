<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Models\Division;
use App\Models\EducationType;
use App\Models\Grade;
use App\Models\Semister;
use App\Models\Stage;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\CourseService;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $courses = $this->courseService->getAllPaginated();

        return view('dashboard.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create($teacher_id = null): View
    {
        $educationTypes = EducationType::all();
        $stages = Stage::all();
        $grades = Grade::all();
        $divisions = Division::all();
        $semisters = Semister::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $selectedTeacher = null;
        if ($teacher_id) {
            $selectedTeacher = Teacher::find($teacher_id);
        }
        return view('dashboard.courses.create',compact('educationTypes', 'stages', 'grades','divisions','semisters','subjects','teachers','selectedTeacher'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CourseRequest $request
     * @return RedirectResponse
     */
    public function store(CourseRequest $request): RedirectResponse
    {
        try {
            $course = $this->courseService->create($request->all());

            return redirect()->route('courses.show',$course->id)
                ->with('success', 'Course created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Course: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Course $course
     * @return View
     */
    public function show(Course $course): View
    {
        return view('dashboard.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Course $course
     * @return View
     */
    public function edit(Course $course): View
    {
        $teachers = Teacher::all();
        $educationTypes = EducationType::all();
        $stages = Stage::all();
        $grades = Grade::all();
        $divisions = Division::all();
        $semisters = Semister::all();
        $subjects = Subject::all();
        return view('dashboard.courses.edit', compact('course','teachers','educationTypes','stages','grades','divisions','semisters','subjects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CourseRequest $request
     * @param Course $course
     * @return RedirectResponse
     */
    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        try {
            $this->courseService->update($course, $request->validated());

            return redirect()->route('courses.index')
                ->with('success', 'Course updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Course: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Course $course
     * @return RedirectResponse
     */
    public function destroy(Course $course): RedirectResponse
    {
        try {
            $this->courseService->delete($course);

            return redirect()->route('courses.index')
                ->with('success', 'Course deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Course: ' . $e->getMessage());
        }
    }
}
