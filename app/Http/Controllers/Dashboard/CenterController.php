<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CenterRequest;
use App\Services\CenterService;
use App\Models\Center;
use Illuminate\Http\Request;
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
     *
     * @return View
     */
    public function index(): View
    {
        $centers = $this->centerService->getAllPaginated();

        return view('dashboard.centers.index', compact('centers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('dashboard.centers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CenterRequest $request
     * @return RedirectResponse
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
                ->with('error', 'Error creating Center: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Center $center
     * @return View
     */
    public function show(Center $center): View
    {
        return view('dashboard.centers.show', compact('center'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Center $center
     * @return View
     */
    public function edit(Center $center): View
    {
        return view('dashboard.centers.edit', compact('center'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CenterRequest $request
     * @param Center $center
     * @return RedirectResponse
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
                ->with('error', 'Error updating Center: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Center $center
     * @return RedirectResponse
     */
    public function destroy(Center $center): RedirectResponse
    {
        try {
            $this->centerService->delete($center);

            return redirect()->route('centers.index')
                ->with('success', 'Center deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Center: ' . $e->getMessage());
        }
    }
}
