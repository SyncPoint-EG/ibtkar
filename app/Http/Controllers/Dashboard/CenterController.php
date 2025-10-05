<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CenterRequest;
use App\Models\Center;
use App\Services\CenterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CenterController extends Controller
{
    protected CenterService $centerService;

    public function __construct(CenterService $centerService)
    {
        $this->centerService = $centerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $centers = $this->centerService->getAllPaginated();

        return view('dashboard.centers.index', compact('centers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.centers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CenterRequest $request): RedirectResponse
    {
        try {
            $this->centerService->create($request->validated());

            return redirect()->route('centers.index')
                ->with('success', 'Center created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Center: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Center $center): View
    {
        return view('dashboard.centers.show', compact('center'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Center $center): View
    {
        return view('dashboard.centers.edit', compact('center'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CenterRequest $request, Center $center): RedirectResponse
    {
        try {
            $this->centerService->update($center, $request->validated());

            return redirect()->route('centers.index')
                ->with('success', 'Center updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Center: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Center $center): RedirectResponse
    {
        try {
            $this->centerService->delete($center);

            return redirect()->route('centers.index')
                ->with('success', 'Center deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Center: '.$e->getMessage());
        }
    }
}
