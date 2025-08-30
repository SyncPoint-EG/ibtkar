<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentApprovalService
{
    /**
     * Get all pending payments with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPendingPaymentsPaginated(int $perPage): LengthAwarePaginator
    {
//        return Payment::where('payment_status', Payment::PAYMENT_STATUS['pending'])
        return Payment::with('student') // Assuming a relationship exists
            ->paginate($perPage);
    }

    /**
     * Accept a pending payment.
     *
     * @param int $paymentId
     * @return void
     * @throws \Exception
     */
    public function acceptPayment(int $paymentId): void
    {
        $payment = Payment::findOrFail($paymentId);

        if ($payment->payment_status !== Payment::PAYMENT_STATUS['pending']) {
            throw new \Exception('Payment is not in pending status.');
        }

        $payment->payment_status = Payment::PAYMENT_STATUS['approved'];
        $payment->save();

        // If the payment was for wallet top-up, add amount to student's wallet
        if ($payment->payment_method === 'wallet') {
            $student = Student::find($payment->student_id);
            if ($student) {
                $student->wallet += $payment->amount;
                $student->save();
            }
        }
    }

    /**
     * Reject a pending payment.
     *
     * @param int $paymentId
     * @return void
     * @throws \Exception
     */
    public function rejectPayment(int $paymentId): void
    {
        $payment = Payment::findOrFail($paymentId);

        if ($payment->payment_status !== Payment::PAYMENT_STATUS['pending']) {
            throw new \Exception('Payment is not in pending status.');
        }

        $payment->payment_status = Payment::PAYMENT_STATUS['rejected'];
        $payment->save();
    }
}
