<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
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
