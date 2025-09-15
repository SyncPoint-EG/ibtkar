<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChargeRequest;
use App\Http\Requests\PaymentRequest;
use App\Models\Chapter;
use App\Models\Charge;
use App\Models\Code;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Watch;
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
        if($request->payment_method == 'code'){
            $code = Code::where('code',$request->payment_code)->first();

            if(!$code){
                return response()->json([
                    'success' => false,
                    'message' => 'Code not found'
                ]);
            }else{
                if(($code->for == 'lesson' && !$request->lesson_id) || ($code->for == 'chapter' && !$request->chapter_id) || ($code->for == 'course' && !$request->course_id) || $code->for == 'charge'){
                    return response()->json([
                        'success' => false,
                        'message' => 'Code is not applicable'
                    ]);
                }
                if($code->number_of_uses > 0 || now() > $code->expires_at){
                    return response()->json([
                        'status' => false,
                        'message'  => 'الكود مستخدم من قبل'
                    ]);
                }else{
                    $code->increment('number_of_uses'); ;
                    $code->save();
                }
            }

        }
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
        if($request->course_id){
            $course = Course::find($request->course_id);
            if($request->payment_method == 'code'){
                $code = Code::where('code',$request->payment_code)->first();
                if($code->price != $course->price){
                    return response()->json([
                        'status' => false,
                        'message'  => 'قيمة الكود لا تتناسب مع سعر الكورس'
                    ]);
                }
            }
            $chapterIds = $course->chapters->pluck('id')->toArray();
            $lessonIds = Lesson::whereIn('chapter_id', $chapterIds)->pluck('id')->toArray();
            Watch::where('student_id', $student_id)->whereIn('lesson_id', $lessonIds)->update(['count'=>3]);
        }
        if($request->chapter_id){
            $lessonIds = Lesson::where('chapter_id', $request->chapter_id)->pluck('id')->toArray();
            $chapter = Chapter::find($request->chapter_id);
            if($request->payment_method == 'code'){
                $code = Code::where('code',$request->payment_code)->first();
                if($code->price != $chapter->price){
                    return response()->json([
                        'status' => false,
                        'message'  => 'قيمة الكود لا تتناسب مع سعر الكورس'
                    ]);
                }
            }
            Watch::where('student_id', $student_id)->whereIn('lesson_id', $lessonIds)->update(['count'=>3]);

        }
        if($request->lesson_id){
            $lesson = Lesson::find($request->lesson_id);
            if($request->payment_method == 'code'){
                $code = Code::where('code',$request->payment_code)->first();
                if($code->price != $lesson->price){
                    return response()->json([
                        'status' => false,
                        'message'  => 'قيمة الكود لا تتناسب مع سعر الكورس'
                    ]);
                }
            }
            Watch::where('student_id', $student_id)->where('lesson_id', $request->lesson_id)->update(['count'=>3]);
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
        if($request->payment_method == 'code'){
            $code = Code::where('code',$request->payment_code)->first();
            if(!$code){
                return response()->json([
                    'status' => false,
                    'message'  => 'Code not found'
                ]);
            }
            if($code->price != $amount || $code->number_of_uses > 1){
                return response()->json([
                    'status' => false,
                    'message' => 'You cant use this code'
                ]);
            }
            $validated['payment_status'] = Payment::PAYMENT_STATUS['approved'];
            $code->increment('number_of_uses'); ;
            $code->save();

        }else{
            $validated['payment_status'] = Payment::PAYMENT_STATUS['pending'];
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
