<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $amount = 0 ;
        if($request->course_id){
            $amount = Course::find($request->course_id)->price ;
        }elseif ($request->chapter_id){
            $amount = Chapter::find($request->chapter_id)->price ;
        }elseif ($request->lesson_id){
            $amount = Lesson::find($request->lesson_id)->price ;
        }
        $payment = Payment::create([
            'student_id' => $request->student_id,
            'course_id' => $request->course_id,
            'chapter_id' => $request->chapter_id,
            'lesson_id' => $request->lesson_id,
            'payment_method' => $request->payment_method,
            'amount' => $amount ,
            'total_amount' => $amount

        ]);
    }
}
