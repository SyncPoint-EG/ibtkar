<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonRequest;
use App\Models\Chapter;
use App\Services\LessonService;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LessonController extends Controller
{
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
            $this->lessonService->create($request->validated());

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
        return view('dashboard.lessons.show', compact('lesson'));
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
            $this->lessonService->update($lesson, $request->validated());

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
}
