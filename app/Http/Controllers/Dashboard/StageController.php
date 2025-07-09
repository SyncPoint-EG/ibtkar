<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StageRequest;
use App\Services\StageService;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StageController extends Controller
{
    protected StageService $stageService;

    public function __construct(StageService $stageService)
    {
        $this->stageService = $stageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $stages = $this->stageService->getAllPaginated();

        return view('dashboard.stages.index', compact('stages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('dashboard.stages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StageRequest $request
     * @return RedirectResponse
     */
    public function store(StageRequest $request): RedirectResponse
    {
        try {
            $this->stageService->create($request->validated());

            return redirect()->route('stages.index')
                ->with('success', 'Stage created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Stage: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Stage $stage
     * @return View
     */
    public function show(Stage $stage): View
    {
        return view('dashboard.stages.show', compact('stage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Stage $stage
     * @return View
     */
    public function edit(Stage $stage): View
    {
        return view('dashboard.stages.edit', compact('stage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StageRequest $request
     * @param Stage $stage
     * @return RedirectResponse
     */
    public function update(StageRequest $request, Stage $stage): RedirectResponse
    {
        try {
            $this->stageService->update($stage, $request->validated());

            return redirect()->route('stages.index')
                ->with('success', 'Stage updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Stage: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Stage $stage
     * @return RedirectResponse
     */
    public function destroy(Stage $stage): RedirectResponse
    {
        try {
            $this->stageService->delete($stage);

            return redirect()->route('stages.index')
                ->with('success', 'Stage deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Stage: ' . $e->getMessage());
        }
    }
}
