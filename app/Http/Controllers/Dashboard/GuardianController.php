<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardianRequest;
use App\Services\GuardianService;
use App\Models\Guardian;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GuardianController extends Controller
{
    protected GuardianService $guardianService;

    public function __construct(GuardianService $guardianService)
    {
        $this->guardianService = $guardianService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $guardians = $this->guardianService->getAllPaginated();

        return view('dashboard.guardians.index', compact('guardians'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('dashboard.guardians.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GuardianRequest $request
     * @return RedirectResponse
     */
    public function store(GuardianRequest $request): RedirectResponse
    {
        try {
            $this->guardianService->create($request->validated());

            return redirect()->route('guardians.index')
                ->with('success', 'Guardian created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Guardian: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Guardian $guardian
     * @return View
     */
    public function show(Guardian $guardian): View
    {
        return view('dashboard.guardians.show', compact('guardian'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Guardian $guardian
     * @return View
     */
    public function edit(Guardian $guardian): View
    {
        return view('dashboard.guardians.edit', compact('guardian'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GuardianRequest $request
     * @param Guardian $guardian
     * @return RedirectResponse
     */
    public function update(GuardianRequest $request, Guardian $guardian): RedirectResponse
    {
        try {
            $this->guardianService->update($guardian, $request->validated());

            return redirect()->route('guardians.index')
                ->with('success', 'Guardian updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Guardian: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Guardian $guardian
     * @return RedirectResponse
     */
    public function destroy(Guardian $guardian): RedirectResponse
    {
        try {
            $this->guardianService->delete($guardian);

            return redirect()->route('guardians.index')
                ->with('success', 'Guardian deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Guardian: ' . $e->getMessage());
        }
    }
}
