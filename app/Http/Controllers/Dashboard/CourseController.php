<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\Division;
use App\Models\EducationType;
use App\Models\Grade;
use App\Models\Semister;
use App\Models\Stage;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Watch;
use App\Services\CourseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     */
    public function index(): View
    {
        $courses = $this->courseService->getAllPaginated();

        return view('dashboard.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
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

        return view('dashboard.courses.create', compact('educationTypes', 'stages', 'grades', 'divisions', 'semisters', 'subjects', 'teachers', 'selectedTeacher'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['is_featured'] = $request->has('is_featured');
            if ($request->hasFile('website_image')) {
                $data['website_image'] = $request->file('website_image');
            }
            $course = $this->courseService->create($data);

            return redirect()->route('courses.show', $course->id)
                ->with('success', 'Course created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Course: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course): View
    {
        $students = $this->courseService->getStudents($course);

        return view('dashboard.courses.show', compact('course', 'students'));
    }

    /**
     * Show the form for editing the specified resource.
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

        return view('dashboard.courses.edit', compact('course', 'teachers', 'educationTypes', 'stages', 'grades', 'divisions', 'semisters', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['is_featured'] = $request->has('is_featured');
            if ($request->hasFile('website_image')) {
                $data['website_image'] = $request->file('website_image');
            }
            $this->courseService->update($course, $data);

            return redirect()->route('courses.index')
                ->with('success', 'Course updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Course: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
        try {
            $this->courseService->delete($course);

            return redirect()->route('courses.index')
                ->with('success', 'Course deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Course: '.$e->getMessage());
        }
    }

    public function toggleFeatured(Course $course): \Illuminate\Http\JsonResponse
    {
        try {
            $course->is_featured = ! $course->is_featured;
            $course->save();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.course.featured_status_updated'),
                'is_featured' => $course->is_featured,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('dashboard.common.error').': '.$e->getMessage(),
            ], 500);
        }
    }

    public function updateWatches(Request $request, Course $course, Student $student)
    {
        $request->validate([
            'watches' => 'required|integer|min=0',
        ]);

        foreach ($course->chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                Watch::updateOrCreate(
                    ['student_id' => $student->id, 'lesson_id' => $lesson->id],
                    ['watches' => $request->watches]
                );
            }
        }

        return redirect()->back()->with('success', 'Watches updated successfully.');
    }
}
