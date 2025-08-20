<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use App\Models\Payment;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function getLessons()
    {
        $perPage = \request()->query('perPage',10);
        $lessons = Lesson::where('is_featured',1)->query()->paginate($perPage);
        return LessonResource::collection($lessons);
    }
    public function getLesson(Lesson $lesson)
    {
        $chapter = $lesson->chapter;
        $course = $chapter->course;

        $student = auth('student')->user();
        $is_purchased = Payment::where('student_id',$student->id)->where(function ($query) use ($lesson ,$chapter, $course) {
            $query->where('lesson_id',$lesson->id)
                ->orWhere('chapter_id',$chapter->id)
                ->orWhere('course_id',$course->id);
        })->exists();
        if($is_purchased){
            return new LessonResource($lesson);
        }else{
            return  response()->json([
                'status' => false ,
                'message' => 'لا يمكنك مشاهدة هذا الفيديو'
            ]);
        }
    }
}
