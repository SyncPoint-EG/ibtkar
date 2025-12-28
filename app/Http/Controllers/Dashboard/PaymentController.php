<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use App\Services\StudentPaymentService;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function __construct(private readonly StudentPaymentService $studentPaymentService)
    {
    }

    public function store(PaymentRequest $request)
    {
        $student = auth('student')->user();

        if (! $student) {
            return response()->json([
                'status' => false,
                'message' => 'Student is not authenticated.',
            ], 401);
        }

        return $this->studentPaymentService->create($request, $student);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Payment $payment
     * @return RedirectResponse
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->back()->with('success', __('dashboard.payment.deleted_successfully'));
    }
}
