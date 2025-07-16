<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\EducationTypeRequest;
use App\Services\EducationTypeService;
use App\Models\EducationType;
use Illuminate\Http\Request;
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
     *
     * @return View
     */
    public function index(): View
    {
        $educationTypes = $this->educationTypeService->getAllPaginated();

        return view('dashboard.education-types.index', compact('educationTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('dashboard.education-types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EducationTypeRequest $request
     * @return RedirectResponse
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
                ->with('error', 'Error creating EducationType: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param EducationType $educationType
     * @return View
     */
    public function show(EducationType $educationType): View
    {
        return view('dashboard.education-types.show', compact('educationType'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EducationType $educationType
     * @return View
     */
    public function edit(EducationType $educationType): View
    {
        return view('dashboard.education-types.edit', compact('educationType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EducationTypeRequest $request
     * @param EducationType $educationType
     * @return RedirectResponse
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
                ->with('error', 'Error updating EducationType: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param EducationType $educationType
     * @return RedirectResponse
     */
    public function destroy(EducationType $educationType): RedirectResponse
    {
        try {
            $this->educationTypeService->delete($educationType);

            return redirect()->route('education-types.index')
                ->with('success', 'EducationType deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting EducationType: ' . $e->getMessage());
        }
    }
}
