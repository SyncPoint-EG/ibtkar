<?php

namespace App\Services;

use App\Models\Charge;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Pagination\LengthAwarePaginator;

class ChargeApprovalService
{
    /**
     * Get all pending charges with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPendingChargesPaginated(int $perPage): LengthAwarePaginator
    {
        return Charge::where('payment_status', Payment::PAYMENT_STATUS['pending'])
            ->with('student')
            ->paginate($perPage);
    }

    /**
     * Accept a pending charge.
     *
     * @param int $chargeId
     * @return void
     * @throws \Exception
     */
    public function acceptCharge(int $chargeId): void
    {
        $charge = Charge::findOrFail($chargeId);

        if ($charge->payment_status !== Payment::PAYMENT_STATUS['pending']) {
            throw new \Exception('Charge is not in pending status.');
        }

        $charge->payment_status = Payment::PAYMENT_STATUS['accepted'];
        $charge->save();

        $student = Student::find($charge->student_id);
        if ($student) {
            $student->wallet += $charge->amount;
            $student->save();
        }
    }

    /**
     * Reject a pending charge.
     *
     * @param int $chargeId
     * @return void
     * @throws \Exception
     */
    public function rejectCharge(int $chargeId): void
    {
        $charge = Charge::findOrFail($chargeId);

        if ($charge->payment_status !== Payment::PAYMENT_STATUS['pending']) {
            throw new \Exception('Charge is not in pending status.');
        }

        $charge->payment_status = Payment::PAYMENT_STATUS['rejected'];
        $charge->save();
    }
}
