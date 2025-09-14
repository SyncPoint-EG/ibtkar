<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Watch;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function getLessons()
    {
        $perPage = \request()->query('perPage',10);
        $student = auth('student')->user();
//        return Lesson::whereHas('chapter.course')->get() ;
        $lessons = Lesson::where('is_featured',1)->whereHas('chapter.course',function ($q) use ($student){
            $q->where('stage_id',$student->stage_id);
                $q->where('grade_id',$student->grade_id);
//            if($student->division_id){
//                $q->where(function ($qq) use ($student) {
//                    $qq->where('division_id', $student->division_id)
//                        ->orWhereNull('division_id');
//                });
//            }
        })->paginate($perPage);
        return LessonResource::collection($lessons);
    }
    public function getLesson(Lesson $lesson)
    {
        $chapter = $lesson->chapter;
        $course = $chapter->course;

        $student = auth('student')->user();
        $is_free = $lesson->price == 0 || $chapter->price == 0 || $course->price == 0;
        $is_purchased = Payment::where('student_id',$student->id)->where(function ($query) use ($lesson ,$chapter, $course) {
            $query->where('lesson_id',$lesson->id)
                ->orWhere('chapter_id',$chapter->id)
                ->orWhere('course_id',$course->id);
        })->exists();
        $max_watches = $student->watches()->where('lesson_id', $lesson->id)->first();
        if (!$is_free && $max_watches && $max_watches->count > 3) {
            return response()->json([
                'status' => false,
                'message' => 'لقد شاهدت هذا الدرس بالفعل أكثر من 3 مرات',
            ]);
        }
        if($is_purchased || $is_free){
            return new LessonResource($lesson);
        }else{
            return  response()->json([
                'status' => false ,
                'message' => 'لا يمكنك مشاهدة هذا الفيديو'
            ]);
        }
    }
    public function watch($id)
    {

        $student = auth('student')->user();
        $watch = $student->watches()->where('lesson_id',$id)->first();
        if(!$watch){
            $watch = new Watch();
            $watch->student_id = $student->id;
            $watch->lesson_id = $id;
            $watch->save();
        }elseif ($watch && $watch->count < 3) {
            $watch->count += 1;
            $watch->save();
        }else{
            return response()->json([
                'status' => false,
                'message' => 'لقد شاهدت هذا الدرس بالفعل أكثر من 3 مرات',
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'تم مشاهدة الدرس بنجاح',
        ]);
    }
}
