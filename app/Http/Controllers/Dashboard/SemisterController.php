<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SemisterRequest;
use App\Services\SemisterService;
use App\Models\Semister;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SemisterController extends Controller
{
    protected SemisterService $semisterService;

    public function __construct(SemisterService $semisterService)
    {
        $this->semisterService = $semisterService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $semisters = $this->semisterService->getAllPaginated();

        return view('dashboard.semisters.index', compact('semisters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('dashboard.semisters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SemisterRequest $request
     * @return RedirectResponse
     */
    public function store(SemisterRequest $request): RedirectResponse
    {
        try {
            $this->semisterService->create($request->validated());

            return redirect()->route('semisters.index')
                ->with('success', 'Semister created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Semister: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Semister $semister
     * @return View
     */
    public function show(Semister $semister): View
    {
        return view('dashboard.semisters.show', compact('semister'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Semister $semister
     * @return View
     */
    public function edit(Semister $semister): View
    {
        return view('dashboard.semisters.edit', compact('semister'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SemisterRequest $request
     * @param Semister $semister
     * @return RedirectResponse
     */
    public function update(SemisterRequest $request, Semister $semister): RedirectResponse
    {
        try {
            $this->semisterService->update($semister, $request->validated());

            return redirect()->route('semisters.index')
                ->with('success', 'Semister updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Semister: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Semister $semister
     * @return RedirectResponse
     */
    public function destroy(Semister $semister): RedirectResponse
    {
        try {
            $this->semisterService->delete($semister);

            return redirect()->route('semisters.index')
                ->with('success', 'Semister deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Semister: ' . $e->getMessage());
        }
    }
}
