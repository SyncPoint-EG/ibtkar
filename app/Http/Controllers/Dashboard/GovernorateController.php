<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\GovernorateRequest;
use App\Models\Governorate;
use App\Services\GovernorateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GovernorateController extends Controller
{
    protected GovernorateService $governorateService;

    public function __construct(GovernorateService $governorateService)
    {
        $this->governorateService = $governorateService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $governorates = $this->governorateService->getAllPaginated();

        return view('dashboard.governorates.index', compact('governorates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.governorates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GovernorateRequest $request): RedirectResponse
    {
        try {
            $this->governorateService->create($request->validated());

            return redirect()->route('governorates.index')
                ->with('success', 'Governorate created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Governorate: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Governorate $governorate): View
    {
        return view('dashboard.governorates.show', compact('governorate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Governorate $governorate): View
    {
        return view('dashboard.governorates.edit', compact('governorate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GovernorateRequest $request, Governorate $governorate): RedirectResponse
    {
        try {
            $this->governorateService->update($governorate, $request->validated());

            return redirect()->route('governorates.index')
                ->with('success', 'Governorate updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Governorate: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Governorate $governorate): RedirectResponse
    {
        try {
            $this->governorateService->delete($governorate);

            return redirect()->route('governorates.index')
                ->with('success', 'Governorate deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Governorate: '.$e->getMessage());
        }
    }
}
