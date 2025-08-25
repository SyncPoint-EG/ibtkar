<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChargeRequest;
use App\Http\Requests\PaymentRequest;
use App\Models\Chapter;
use App\Models\Charge;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(PaymentRequest $request)
    {
        $student = auth('student')->user();
        $student_id = $student->id;
        $amount = $this->getAmount($request);
        $validated = $request->validated();
        $validated['student_id'] = $student_id;
        $validated['amount'] = $amount;
        $validated['total_amount'] = $amount;
        if(in_array($request->payment_method , ['instapay', 'wallet'])){
            $validated['payment_status'] = Payment::PAYMENT_STATUS['pending'];
        }else{
            $validated['payment_status'] = Payment::PAYMENT_STATUS['approved'];
        }
        if($request->payment_method == 'ibtkar_wallet'){
            if($student->wallet < $amount){
                return response()->json([
                    'status' => false,
                    'message'  => 'لا يوجد لديك رصيد كافي في المحفظة للشراء'
                ]);
            }
            $student->wallet -= $amount;
            $student->save();

        }
        $payment = Payment::create($validated);

        return response()->json([
            'status' => true,
            'message'  => 'تمت عملية الشراء بنجاح'
        ]);
    }
    private function getAmount(Request $request)
    {
        $amount = 0 ;
        if($request->course_id){
            $amount = Course::find($request->course_id)->price ;
        }elseif ($request->chapter_id){
            $amount = Chapter::find($request->chapter_id)->price ;
        }elseif ($request->lesson_id){
            $amount = Lesson::find($request->lesson_id)->price ;
        }
        return $amount ;
    }

    public function chargeWallet(ChargeRequest $request)
    {
        $validated = $request->validated();
        $student = auth('student')->user();
        $amount = $request->amount;
        if(in_array($request->payment_method , ['instapay', 'wallet'])){
            $validated['payment_status'] = Payment::PAYMENT_STATUS['pending'];
        }else{
            $validated['payment_status'] = Payment::PAYMENT_STATUS['accepted'];
        }
        $validated['student_id'] = $student->id;
        $validated['amount'] = $amount;
        Charge::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'تم الشحن و بانتظار موافقة الادمن'
        ]);
    }
}
