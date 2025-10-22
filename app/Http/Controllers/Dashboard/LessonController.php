<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportGiftedStudentsRequest;
use App\Imports\GiftedStudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\LessonRequest;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\LessonAttachment;
use App\Models\Student;
use App\Models\Watch;
use App\Services\LessonService;
use App\Traits\FirebaseNotify;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonController extends Controller
{
    use FirebaseNotify;

    protected LessonService $lessonService;

    public function __construct(LessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $teacher_id = null): View
    {
        $filters = $request->only(['course_id', 'chapter_id', 'name', 'created_at', 'date','teacher_id','grade_id']);
        if ($teacher_id) {
            $filters['teacher_id'] = $teacher_id;
        }
        $lessons = $this->lessonService->getAllPaginated(15, $filters);
        $teachers = \App\Models\Teacher::all();
        $courses = \App\Models\Course::all();
        $chapters = \App\Models\Chapter::all();
        $grades = \App\Models\Grade::all();

        return view('dashboard.lessons.index', compact('lessons', 'teachers', 'courses', 'chapters','grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($chapter_id = null): View
    {
        $chapters = Chapter::all();
        $selectedChapterId = null;
        $selectedChapter = null;

        // If chapter_id is provided, validate it and set as selected
        if ($chapter_id) {
            $selectedChapter = Chapter::find($chapter_id);

            // If chapter exists, set it as selected
            if ($selectedChapter) {
                $selectedChapterId = $selectedChapter->id;
            }
        }

        return view('dashboard.lessons.create', compact('chapters', 'selectedChapterId', 'selectedChapter'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LessonRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['is_featured'] = $request->has('is_featured');
            $data['date'] = $request->date;

            $lesson = $this->lessonService->create($data);

            //            $students = Student::all();
            //            $title = 'New Lesson Added';
            //            $body = 'A new lesson \'' . $lesson->name . '\' has been added.';
            //            $data = [
            //                'lesson_id' => $lesson->id,
            //            ];
            //
            //            $this->sendAndStoreFirebaseNotification($students, $title, $body, $data);

            return redirect()->route('lessons.index')
                ->with('success', 'Lesson created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Lesson: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson): View
    {
        $students = $this->lessonService->getStudents($lesson);
        $totalStudents = $students->count();
        $watchedStudents = 0;
        foreach($students as $student){
            if($student->watches()->where('lesson_id', $lesson->id)->exists()){
                $watchedStudents++;
            }
        }

//        $chart = new \App\Charts\LessonStudentsChart;
//        $chart->labels(['Watched', 'Not Watched']);
//        $chart->dataset('My dataset', 'doughnut', [$watchedStudents, $totalStudents - $watchedStudents]);

        return view('dashboard.lessons.show', compact('lesson', 'students','watchedStudents','totalStudents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson): View
    {
        $chapters = Chapter::all();

        return view('dashboard.lessons.edit', compact('lesson', 'chapters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LessonRequest $request, Lesson $lesson): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['is_featured'] = $request->has('is_featured');
            $data['date'] = $request->date;

            $this->lessonService->update($lesson, $data);

            return redirect()->route('lessons.index')
                ->with('success', 'Lesson updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Lesson: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson): RedirectResponse
    {
        try {
            Watch::where('lesson_id',$lesson->id)->delete();
            LessonAttachment::where('lesson_id',$lesson->id)->delete();
            $this->lessonService->delete($lesson);

            return redirect()->route('lessons.index')
                ->with('success', 'Lesson deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Lesson: '.$e->getMessage());
        }
    }

    public function toggleFeatured(Lesson $lesson): JsonResponse
    {
        try {
            $lesson->is_featured = ! $lesson->is_featured;
            $lesson->save();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.lesson.featured_status_updated'),
                'is_featured' => $lesson->is_featured,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('dashboard.common.error').': '.$e->getMessage(),
            ], 500);
        }
    }

    public function teachers()
    {
        $teachers = \App\Models\Teacher::all();
        foreach ($teachers as $teacher) {
            $lessonsCount = 0;
            foreach ($teacher->courses as $course) {
                foreach ($course->chapters as $chapter) {
                    $lessonsCount += $chapter->lessons->count();
                }
            }
            $teacher->lessons_count = $lessonsCount;
        }

        return view('dashboard.lessons.teachers', compact('teachers'));
    }

    public function teacherGrades($teacher_id)
    {
        $teacher = \App\Models\Teacher::findOrFail($teacher_id);
        $grades = $teacher->grades;
        return view('dashboard.lessons.teacher-grades', compact('teacher', 'grades'));
    }

    public function updateWatches(Request $request, Lesson $lesson, Student $student)
    {
        $request->validate([
            'watches' => 'required|integer|min=0',
        ]);

        Watch::updateOrCreate(
            ['student_id' => $student->id, 'lesson_id' => $lesson->id],
            ['watches' => $request->watches]
        );

        return redirect()->back()->with('success', 'Watches updated successfully.');
    }

    public function exportStudents(Lesson $lesson)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LessonStudentsExport($lesson), 'lesson-'.$lesson->id.'-students.xlsx');
    }

    public function importStudents(ImportGiftedStudentsRequest $request, Lesson $lesson)
    {
        try {
            $import = new GiftedStudentsImport($lesson);
            Excel::import($import, $request->file('file'));

            $importedCount = $import->importedCount;
            $notFoundPhones = $import->notFoundPhones;

            $messages = [];
            if ($importedCount > 0) {
                $messages[] = "Successfully imported {$importedCount} students.";
            }

            if (count($notFoundPhones) > 0) {
                $messages[] = "The following phone numbers were not found: " . implode(', ', $notFoundPhones);
            }

            if (empty($messages)) {
                $messages[] = 'No new students were imported.';
            }

            return redirect()->back()->with('success', implode(' ', $messages));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred during import: ' . $e->getMessage());
        }
    }
}
