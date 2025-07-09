<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\DivisionRequest;
use App\Models\Grade;
use App\Models\Stage;
use App\Services\DivisionService;
use App\Models\Division;
use Illuminate\Http\Request;
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
     *
     * @return View
     */
    public function index(): View
    {
        $divisions = $this->divisionService->getAllPaginated();

        return view('dashboard.divisions.index', compact('divisions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $grades = Grade::all();
        $stages = Stage::all();
        return view('dashboard.divisions.create', compact('grades','stages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DivisionRequest $request
     * @return RedirectResponse
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
                ->with('error', 'Error creating Division: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Division $division
     * @return View
     */
    public function show(Division $division): View
    {
        return view('dashboard.divisions.show', compact('division'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Division $division
     * @return View
     */
    public function edit(Division $division): View
    {
        $grades = Grade::all();
        $stages = Stage::all();
        return view('dashboard.divisions.edit', compact('division','grades','stages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DivisionRequest $request
     * @param Division $division
     * @return RedirectResponse
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
                ->with('error', 'Error updating Division: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Division $division
     * @return RedirectResponse
     */
    public function destroy(Division $division): RedirectResponse
    {
        try {
            $this->divisionService->delete($division);

            return redirect()->route('divisions.index')
                ->with('success', 'Division deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Division: ' . $e->getMessage());
        }
    }
}
