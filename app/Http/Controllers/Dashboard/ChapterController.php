<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChapterRequest;
use App\Models\Course;
use App\Services\ChapterService;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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
     *
     * @return View
     */
    public function index(): View
    {
        $chapters = $this->chapterService->getAllPaginated();

        return view('dashboard.chapters.index', compact('chapters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create($course_id = null): View
    {
        $courses = Course::select('id', 'name')->get();
        $selectedCourse = null;
        if ($course_id) {
            $selectedCourse = Course::find($course_id);
        }
        return view('dashboard.chapters.create',compact('courses','selectedCourse'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChapterRequest $request
     * @return RedirectResponse
     */
    public function store(ChapterRequest $request): RedirectResponse
    {
        try {
            $this->chapterService->create($request->validated());

            return redirect()->back()->with('success', 'Chapter created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Chapter: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Chapter $chapter
     * @return View
     */
    public function show(Chapter $chapter): View
    {
        return view('dashboard.chapters.show', compact('chapter'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Chapter $chapter
     * @return View
     */
    public function edit(Chapter $chapter): View
    {
        $courses = Course::select('id', 'name')->get();
        return view('dashboard.chapters.edit', compact('chapter','courses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ChapterRequest $request
     * @param Chapter $chapter
     * @return RedirectResponse
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
                ->with('error', 'Error updating Chapter: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Chapter $chapter
     * @return RedirectResponse
     */
    public function destroy(Chapter $chapter): RedirectResponse
    {
        try {
            $this->chapterService->delete($chapter);

            return redirect()->route('chapters.index')
                ->with('success', 'Chapter deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Chapter: ' . $e->getMessage());
        }
    }
}
