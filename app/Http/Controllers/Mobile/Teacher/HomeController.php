<?php

namespace App\Http\Controllers\Mobile\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttachmentResource;
use App\Http\Resources\ExamResource;
use App\Http\Resources\HomeworkResource;
use App\Models\Exam;
use App\Models\Homework;
use App\Models\LessonAttachment;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getExams(){
        $teacher = auth()->guard('teacher')->user();
        $exams = Exam::where(function ($query) use ($teacher) {
            $query->whereHas('lesson.chapter.course', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })->orWhere('teacher_id', $teacher->id);
        })->where('status', 1)->whereDate('start_date','>',now())->get();

        return ExamResource::collection($exams);
    }


    public function getHomeworks(){
        $teacher = auth()->guard('teacher')->user();
        $homework = Homework::whereHas('lesson.chapter.course', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->where('status', 1)->whereDate('due_date','>',now())->get();
        return HomeworkResource::collection($homework);
    }

    public function getAttachments()
    {
        $teacher = auth()->guard('teacher')->user();
        $attachments = LessonAttachment::whereHas('lesson.chapter.course', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->whereDate('due_date','>',now())->paginate(15);
        return AttachmentResource::collection($attachments);
    }
}
