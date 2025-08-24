<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use App\Models\Payment;
use Illuminate\Http\Request;

class PurchasedLessonsController extends Controller
{
    public function index(Request $request)
    {
        $student = auth('student')->user();
        $subjectId = $request->input('subject_id');

        $payments = Payment::where('student_id', $student->id)
            ->where('payment_status', Payment::PAYMENT_STATUS['accepted'])
            ->get();

        $lessonIds = $payments->whereNotNull('lesson_id')->pluck('lesson_id');
        $chapterIds = $payments->whereNotNull('chapter_id')->pluck('chapter_id');
        $courseIds = $payments->whereNotNull('course_id')->pluck('course_id');

        $lessons = Lesson::query()
            ->whereIn('id', $lessonIds)
            ->orWhereIn('chapter_id', $chapterIds)
            ->orWhereHas('chapter', function ($q) use ($courseIds) {
                $q->whereIn('course_id', $courseIds);
            })
            ->when($subjectId, function ($query) use ($subjectId) {
                $query->whereHas('chapter.course', function ($q) use ($subjectId) {
                    $q->where('subject_id', $subjectId);
                });
            })
            ->get();

        return response()->json([
            'status' => true,
            'data' => LessonResource::collection($lessons->unique('id'))
        ]);
    }
}
