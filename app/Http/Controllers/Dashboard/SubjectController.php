<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectRequest;
use App\Models\Subject;
use App\Services\SubjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubjectController extends Controller
{
    protected SubjectService $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $subjects = $this->subjectService->getAllPaginated();

        return view('dashboard.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubjectRequest $request): RedirectResponse
    {
        try {
            $this->subjectService->create($request->validated());

            return redirect()->route('subjects.index')
                ->with('success', 'Subject created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Subject: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject): View
    {
        return view('dashboard.subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject): View
    {
        return view('dashboard.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubjectRequest $request, Subject $subject): RedirectResponse
    {
        try {
            $this->subjectService->update($subject, $request->validated());

            return redirect()->route('subjects.index')
                ->with('success', 'Subject updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Subject: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject): RedirectResponse
    {
        try {
            $this->subjectService->delete($subject);

            return redirect()->route('subjects.index')
                ->with('success', 'Subject deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Subject: '.$e->getMessage());
        }
    }
}
