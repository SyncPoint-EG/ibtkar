<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\DivisionRequest;
use App\Models\Division;
use App\Models\Grade;
use App\Models\Stage;
use App\Services\DivisionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DivisionController extends Controller
{
    protected DivisionService $divisionService;

    public function __construct(DivisionService $divisionService)
    {
        $this->divisionService = $divisionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $divisions = $this->divisionService->getAllPaginated();

        return view('dashboard.divisions.index', compact('divisions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $grades = Grade::all();
        $stages = Stage::all();

        return view('dashboard.divisions.create', compact('grades', 'stages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DivisionRequest $request): RedirectResponse
    {
        try {
            $this->divisionService->create($request->validated());

            return redirect()->route('divisions.index')
                ->with('success', 'Division created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Division: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Division $division): View
    {
        return view('dashboard.divisions.show', compact('division'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Division $division): View
    {
        $grades = Grade::all();
        $stages = Stage::all();

        return view('dashboard.divisions.edit', compact('division', 'grades', 'stages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DivisionRequest $request, Division $division): RedirectResponse
    {
        try {
            $this->divisionService->update($division, $request->validated());

            return redirect()->route('divisions.index')
                ->with('success', 'Division updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Division: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Division $division): RedirectResponse
    {
        try {
            $this->divisionService->delete($division);

            return redirect()->route('divisions.index')
                ->with('success', 'Division deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Division: '.$e->getMessage());
        }
    }
}
