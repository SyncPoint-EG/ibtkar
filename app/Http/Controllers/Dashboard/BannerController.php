<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Services\BannerService;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BannerController extends Controller
{
    protected BannerService $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $banners = $this->bannerService->getAllPaginated();

        return view('dashboard.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('dashboard.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BannerRequest $request
     * @return RedirectResponse
     */
    public function store(BannerRequest $request): RedirectResponse
    {
        try {
            $this->bannerService->create($request->validated());

            return redirect()->route('banners.index')
                ->with('success', 'Banner created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Banner: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Banner $banner
     * @return View
     */
    public function show(Banner $banner): View
    {
        return view('dashboard.banners.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Banner $banner
     * @return View
     */
    public function edit(Banner $banner): View
    {
        return view('dashboard.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BannerRequest $request
     * @param Banner $banner
     * @return RedirectResponse
     */
    public function update(BannerRequest $request, Banner $banner): RedirectResponse
    {
        try {
            $this->bannerService->update($banner, $request->validated());

            return redirect()->route('banners.index')
                ->with('success', 'Banner updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Banner: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Banner $banner
     * @return RedirectResponse
     */
    public function destroy(Banner $banner): RedirectResponse
    {
        try {
            $this->bannerService->delete($banner);

            return redirect()->route('banners.index')
                ->with('success', 'Banner deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Banner: ' . $e->getMessage());
        }
    }
}
