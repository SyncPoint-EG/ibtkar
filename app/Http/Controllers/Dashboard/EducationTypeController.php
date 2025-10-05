<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\EducationTypeRequest;
use App\Models\EducationType;
use App\Services\EducationTypeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EducationTypeController extends Controller
{
    protected EducationTypeService $educationTypeService;

    public function __construct(EducationTypeService $educationTypeService)
    {
        $this->educationTypeService = $educationTypeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $educationTypes = $this->educationTypeService->getAllPaginated();

        return view('dashboard.education-types.index', compact('educationTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.education-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EducationTypeRequest $request): RedirectResponse
    {
        try {
            $this->educationTypeService->create($request->validated());

            return redirect()->route('education-types.index')
                ->with('success', 'EducationType created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating EducationType: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EducationType $educationType): View
    {
        return view('dashboard.education-types.show', compact('educationType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EducationType $educationType): View
    {
        return view('dashboard.education-types.edit', compact('educationType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EducationTypeRequest $request, EducationType $educationType): RedirectResponse
    {
        try {
            $this->educationTypeService->update($educationType, $request->validated());

            return redirect()->route('education-types.index')
                ->with('success', 'EducationType updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating EducationType: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EducationType $educationType): RedirectResponse
    {
        try {
            $this->educationTypeService->delete($educationType);

            return redirect()->route('education-types.index')
                ->with('success', 'EducationType deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting EducationType: '.$e->getMessage());
        }
    }
}
