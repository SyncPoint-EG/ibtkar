<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChargeRequest;
use App\Http\Requests\PaymentRequest;
use App\Models\Charge;
use App\Models\Code;
use App\Models\Payment;
use App\Services\StudentPaymentService;

class PaymentController extends Controller
{
    public function __construct(private readonly StudentPaymentService $studentPaymentService)
    {
    }

    public function store(PaymentRequest $request)
    {
        $student = auth('student')->user();

        return $this->studentPaymentService->create($request, $student);
    }

    public function chargeWallet(ChargeRequest $request)
    {
        $validated = $request->validated();
        $student = auth('student')->user();
        $amount = $request->amount;
        if ($request->payment_method == 'code') {
            $code = Code::where('code', $request->payment_code)->first();
            if (! $code) {
                return response()->json([
                    'status' => false,
                    'message' => 'Code not found',
                ]);
            }
            if ($code->price != $amount || $code->number_of_uses > 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'You cant use this code',
                ]);
            }
            $validated['payment_status'] = Payment::PAYMENT_STATUS['approved'];
            $code->increment('number_of_uses');
            $code->save();

        } else {
            $validated['payment_status'] = Payment::PAYMENT_STATUS['pending'];
        }
        $validated['student_id'] = $student->id;
        $validated['amount'] = $amount;
        Charge::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'تم الشحن و بانتظار موافقة الادمن',
        ]);
    }
}
