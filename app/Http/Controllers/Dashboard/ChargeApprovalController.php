<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChargeApprovalRequest;
use App\Services\ChargeApprovalService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ChargeApprovalController extends Controller
{
    protected ChargeApprovalService $chargeApprovalService;

    public function __construct(ChargeApprovalService $chargeApprovalService)
    {
        $this->chargeApprovalService = $chargeApprovalService;
    }

    /**
     * Display a listing of pending charge approvals.
     *
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request)
    {
        $charges = $this->chargeApprovalService->getPendingChargesPaginated($request->get('per_page', 15));

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $charges,
                'message' => 'Pending charges retrieved successfully.'
            ]);
        }

        return view('dashboard.charge_approvals.index', compact('charges'));
    }

    /**
     * Accept a pending charge.
     *
     * @param ChargeApprovalRequest $request
     * @param int $chargeId
     * @return RedirectResponse|JsonResponse
     */
    public function accept(ChargeApprovalRequest $request, int $chargeId)
    {
        try {
            $this->chargeApprovalService->acceptCharge($chargeId);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Charge accepted successfully.'
                ]);
            }

            return redirect()->route('charge_approvals.index')
                ->with('success', 'Charge accepted successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error accepting charge: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error accepting charge: ' . $e->getMessage());
        }
    }

    /**
     * Reject a pending charge.
     *
     * @param ChargeApprovalRequest $request
     * @param int $chargeId
     * @return RedirectResponse|JsonResponse
     */
    public function reject(ChargeApprovalRequest $request, int $chargeId)
    {
        try {
            $this->chargeApprovalService->rejectCharge($chargeId);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Charge rejected successfully.'
                ]);
            }

            return redirect()->route('charge_approvals.index')
                ->with('success', 'Charge rejected successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error rejecting charge: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error rejecting charge: ' . $e->getMessage());
        }
    }
}
