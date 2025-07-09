<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\DistrictRequest;
use App\Models\Governorate;
use App\Services\DistrictService;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DistrictController extends Controller
{
    protected DistrictService $districtService;

    public function __construct(DistrictService $districtService)
    {
        $this->districtService = $districtService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $districts = $this->districtService->getAllPaginated();

        return view('dashboard.districts.index', compact('districts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $governorates = Governorate::all();
        return view('dashboard.districts.create', compact('governorates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DistrictRequest $request
     * @return RedirectResponse
     */
    public function store(DistrictRequest $request): RedirectResponse
    {
        try {
            $this->districtService->create($request->validated());

            return redirect()->route('districts.index')
                ->with('success', 'District created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating District: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param District $district
     * @return View
     */
    public function show(District $district): View
    {
        return view('dashboard.districts.show', compact('district'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param District $district
     * @return View
     */
    public function edit(District $district): View
    {
        $governorates = Governorate::all();

        return view('dashboard.districts.edit', compact('district','governorates'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DistrictRequest $request
     * @param District $district
     * @return RedirectResponse
     */
    public function update(DistrictRequest $request, District $district): RedirectResponse
    {
        try {
            $this->districtService->update($district, $request->validated());

            return redirect()->route('districts.index')
                ->with('success', 'District updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating District: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param District $district
     * @return RedirectResponse
     */
    public function destroy(District $district): RedirectResponse
    {
        try {
            $this->districtService->delete($district);

            return redirect()->route('districts.index')
                ->with('success', 'District deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting District: ' . $e->getMessage());
        }
    }
}
