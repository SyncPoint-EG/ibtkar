<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonRequest;
use App\Models\Chapter;
use App\Services\LessonService;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Watch;
use App\Traits\FirebaseNotify;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

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
     *
     * @return View
     */
    public function index(): View
    {
        $lessons = $this->lessonService->getAllPaginated();

        return view('dashboard.lessons.index', compact('lessons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
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
        return view('dashboard.lessons.create',compact('chapters', 'selectedChapterId', 'selectedChapter'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LessonRequest $request
     * @return RedirectResponse
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
                ->with('error', 'Error creating Lesson: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Lesson $lesson
     * @return View
     */
    public function show(Lesson $lesson): View
    {
        $students = $this->lessonService->getStudents($lesson);
        return view('dashboard.lessons.show', compact('lesson', 'students'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Lesson $lesson
     * @return View
     */
    public function edit(Lesson $lesson): View
    {
        $chapters = Chapter::all();
        return view('dashboard.lessons.edit', compact('lesson','chapters'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LessonRequest $request
     * @param Lesson $lesson
     * @return RedirectResponse
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
                ->with('error', 'Error updating Lesson: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lesson $lesson
     * @return RedirectResponse
     */
    public function destroy(Lesson $lesson): RedirectResponse
    {
        try {
            $this->lessonService->delete($lesson);

            return redirect()->route('lessons.index')
                ->with('success', 'Lesson deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Lesson: ' . $e->getMessage());
        }
    }

    public function toggleFeatured(Lesson $lesson): JsonResponse
    {
        try {
            $lesson->is_featured = !$lesson->is_featured;
            $lesson->save();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.lesson.featured_status_updated'),
                'is_featured' => $lesson->is_featured
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('dashboard.common.error') . ': ' . $e->getMessage()
            ], 500);
        }
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
}
