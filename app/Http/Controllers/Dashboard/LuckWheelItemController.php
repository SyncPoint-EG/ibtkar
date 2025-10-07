<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\LuckWheelItemRequest;
use App\Services\LuckWheelItemService;
use App\Models\LuckWheelItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class LuckWheelItemController extends Controller
{
    protected LuckWheelItemService $luckWheelItemService;

    public function __construct(LuckWheelItemService $luckWheelItemService)
    {
        $this->luckWheelItemService = $luckWheelItemService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request)
    {
        $luckWheelItems = $this->luckWheelItemService->getAllPaginated($request->get('per_page', 15));

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $luckWheelItems,
                'message' => 'LuckWheelItems retrieved successfully.'
            ]);
        }

        return view('dashboard.luck-wheel-items.index', compact('luckWheelItems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|JsonResponse
     */
    public function create(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ready to create new LuckWheelItem.'
            ]);
        }

        return view('dashboard.luck-wheel-items.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LuckWheelItemRequest $request
     * @return RedirectResponse|JsonResponse
     */
    public function store(LuckWheelItemRequest $request)
    {
        try {
            $luckWheelItem = $this->luckWheelItemService->create($request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $luckWheelItem,
                    'message' => 'LuckWheelItem created successfully.'
                ], 201);
            }

            return redirect()->route('luck-wheel-items.index')
                ->with('success', 'LuckWheelItem created successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating LuckWheelItem: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating LuckWheelItem: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param LuckWheelItem $luckWheelItem
     * @param Request $request
     * @return View|JsonResponse
     */
    public function show(LuckWheelItem $luckWheelItem, Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $luckWheelItem,
                'message' => 'LuckWheelItem retrieved successfully.'
            ]);
        }

        return view('dashboard.luck-wheel-items.show', compact('luckWheelItem'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param LuckWheelItem $luckWheelItem
     * @param Request $request
     * @return View|JsonResponse
     */
    public function edit(LuckWheelItem $luckWheelItem, Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $luckWheelItem,
                'message' => 'LuckWheelItem ready for editing.'
            ]);
        }

        return view('dashboard.luck-wheel-items.edit', compact('luckWheelItem'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LuckWheelItemRequest $request
     * @param LuckWheelItem $luckWheelItem
     * @return RedirectResponse|JsonResponse
     */
    public function update(LuckWheelItemRequest $request, LuckWheelItem $luckWheelItem)
    {
        try {
            $updatedLuckWheelItem = $this->luckWheelItemService->update($luckWheelItem, $request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $updatedLuckWheelItem,
                    'message' => 'LuckWheelItem updated successfully.'
                ]);
            }

            return redirect()->route('luck-wheel-items.index')
                ->with('success', 'LuckWheelItem updated successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating LuckWheelItem: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating LuckWheelItem: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param LuckWheelItem $luckWheelItem
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function destroy(LuckWheelItem $luckWheelItem, Request $request)
    {
        try {
            $this->luckWheelItemService->delete($luckWheelItem);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'LuckWheelItem deleted successfully.'
                ]);
            }

            return redirect()->route('luck-wheel-items.index')
                ->with('success', 'LuckWheelItem deleted successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting LuckWheelItem: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error deleting LuckWheelItem: ' . $e->getMessage());
        }
    }

    /**
     * Get filtered/searched results
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $results = $this->luckWheelItemService->search($request->all());

            return response()->json([
                'success' => true,
                'data' => $results,
                'message' => 'Search completed successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete resources
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $ids = $request->input('ids', []);
            $deleted = $this->luckWheelItemService->bulkDelete($ids);

            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deleted} luckWheelItems."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk delete error: ' . $e->getMessage()
            ], 500);
        }
    }
}
