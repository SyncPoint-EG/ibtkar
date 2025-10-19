<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChapterRequest;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Division;
use App\Models\Grade;
use App\Models\Stage;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Watch;
use App\Services\ChapterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChapterController extends Controller
{
    protected ChapterService $chapterService;

    public function __construct(ChapterService $chapterService)
    {
        $this->chapterService = $chapterService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Chapter::with([
            'course.teacher',
            'course.stage',
            'course.grade',
            'course.division',
        ]);

        // Name filter
        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }

        // Course filter
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Teacher filter
        if ($request->filled('teacher_id')) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('teacher_id', $request->teacher_id);
            });
        }

        // Stage filter
        if ($request->filled('stage_id')) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('stage_id', $request->stage_id);
            });
        }

        // Grade filter
        if ($request->filled('grade_id')) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('grade_id', $request->grade_id);
            });
        }

        // Division filter
        if ($request->filled('division_id')) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }

        $chapters = $query->paginate(15);

        $courses = Course::all();
        $teachers = Teacher::all();
        $stages = Stage::all();
        $grades = Grade::all();
        $divisions = Division::all();
        $filters = $request->all();

        return view('dashboard.chapters.index', compact('chapters', 'courses', 'teachers', 'stages', 'grades', 'divisions', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($course_id = null): View
    {
        $courses = Course::select('id', 'name')->get();
        $selectedCourse = null;
        if ($course_id) {
            $selectedCourse = Course::find($course_id);
        }

        return view('dashboard.chapters.create', compact('courses', 'selectedCourse'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChapterRequest $request): RedirectResponse
    {
        try {
            $chapter = $this->chapterService->create($request->validated());

            return redirect()->route('chapters.show', $chapter->id)->with('success', 'Chapter created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Chapter: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Chapter $chapter): View
    {
        $students = $this->chapterService->getStudents($chapter);

        return view('dashboard.chapters.show', compact('chapter', 'students'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chapter $chapter): View
    {
        $courses = Course::select('id', 'name')->get();

        return view('dashboard.chapters.edit', compact('chapter', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ChapterRequest $request, Chapter $chapter): RedirectResponse
    {
        try {
            $this->chapterService->update($chapter, $request->validated());

            return redirect()->route('chapters.index')
                ->with('success', 'Chapter updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Chapter: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chapter $chapter): RedirectResponse
    {
        try {
            $this->chapterService->delete($chapter);

            return redirect()->route('chapters.index')
                ->with('success', 'Chapter deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Chapter: '.$e->getMessage());
        }
    }

    public function teachers()
    {
        $teachers = \App\Models\Teacher::all();
        foreach ($teachers as $teacher) {
            $teacher->chapters_count = $teacher->chapters()->count();
        }

        return view('dashboard.chapters.teachers', compact('teachers'));
    }

    public function teacherGrades($teacher_id)
    {
        $teacher = \App\Models\Teacher::findOrFail($teacher_id);
        $grades = $teacher->grades;
        return view('dashboard.chapters.teacher-grades', compact('teacher', 'grades'));
    }

    public function updateWatches(Request $request, Chapter $chapter, Student $student)
    {
        $request->validate([
            'watches' => 'required|integer|min=0',
        ]);

        foreach ($chapter->lessons as $lesson) {
            Watch::updateOrCreate(
                ['student_id' => $student->id, 'lesson_id' => $lesson->id],
                ['watches' => $request->watches]
            );
        }

        return redirect()->back()->with('success', 'Watches updated successfully.');
    }
}
