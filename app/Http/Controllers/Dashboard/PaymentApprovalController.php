<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentApprovalRequest;
use App\Services\PaymentApprovalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentApprovalController extends Controller
{
    protected PaymentApprovalService $paymentApprovalService;

    public function __construct(PaymentApprovalService $paymentApprovalService)
    {
        $this->paymentApprovalService = $paymentApprovalService;
    }

    /**
     * Display a listing of pending payment approvals.
     *
     * @return View|JsonResponse
     */
    public function index(Request $request)
    {
        $payments = $this->paymentApprovalService->getPendingPaymentsPaginated($request->get('per_page', 15));
        $statistics = $this->paymentApprovalService->getPaymentStatistics();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $payments,
                'statistics' => $statistics,
                'message' => 'Pending payments retrieved successfully.',
            ]);
        }

        return view('dashboard.payment_approvals.index', compact('payments', 'statistics'));
    }

    /**
     * Accept a pending payment.
     *
     * @return RedirectResponse|JsonResponse
     */
    public function accept(PaymentApprovalRequest $request, int $paymentId)
    {
        try {
            $this->paymentApprovalService->acceptPayment($paymentId);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment accepted successfully.',
                ]);
            }

            return redirect()->route('payment_approvals.index')
                ->with('success', 'Payment accepted successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error accepting payment: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error accepting payment: '.$e->getMessage());
        }
    }

    /**
     * Reject a pending payment.
     *
     * @return RedirectResponse|JsonResponse
     */
    public function reject(PaymentApprovalRequest $request, int $paymentId)
    {
        try {
            $this->paymentApprovalService->rejectPayment($paymentId);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment rejected successfully.',
                ]);
            }

            return redirect()->route('payment_approvals.index')
                ->with('success', 'Payment rejected successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error rejecting payment: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error rejecting payment: '.$e->getMessage());
        }
    }
}
