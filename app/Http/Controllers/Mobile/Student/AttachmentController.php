<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonAttachmentResource;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use App\Models\LessonAttachment;
use App\Models\Payment;

class AttachmentController extends Controller
{
    public function allAttachments()
    {
        $perPage = \request()->query('perPage', 10);
        $student = auth('student')->user();
        $attachments = LessonAttachment::whereHas('lesson.chapter.course', function ($q) use ($student) {
            $q->where('stage_id', $student->stage_id)
                ->where('grade_id', $student->grade_id)
                ->where('division_id', $student->division_id);
        })->paginate($perPage);

        return LessonAttachmentResource::collection($attachments);
    }

    public function getLesson(Lesson $lesson)
    {
        $chapter = $lesson->chapter;
        $course = $chapter->course;

        $student = auth('student')->user();
        $is_purchased = Payment::where('student_id', $student->id)->where(function ($query) use ($lesson, $chapter, $course) {
            $query->where('lesson_id', $lesson->id)
                ->orWhere('chapter_id', $chapter->id)
                ->orWhere('course_id', $course->id);
        })->exists();
        if ($is_purchased) {
            return new LessonResource($lesson);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'لا يمكنك مشاهدة هذا الفيديو',
            ]);
        }
    }
}
