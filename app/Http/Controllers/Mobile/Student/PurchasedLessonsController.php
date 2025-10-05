<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonResource;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PurchasedLessonsController extends Controller
{
    public function index(Request $request)
    {
        $student = auth('student')->user();
        $subjectId = $request->input('subject_id');

        $payments = Payment::where('student_id', $student->id)
            ->where('payment_status', Payment::PAYMENT_STATUS['approved'])
            ->get();

        $lessonIds = $payments->whereNotNull('lesson_id')->pluck('lesson_id');
        $chapterIds = $payments->whereNotNull('chapter_id')->pluck('chapter_id');
        $courseIds = $payments->whereNotNull('course_id')->pluck('course_id');

        $chapterIds = $chapterIds->merge(Chapter::where('price', 0)->pluck('id'));
        $courseIds = $courseIds->merge(Course::where('price', 0)->pluck('id'));

        $lessons = Lesson::query()
            ->where(function (Builder $query) use ($lessonIds, $chapterIds, $courseIds) {
                $query->whereIn('id', $lessonIds)
                    ->orWhereIn('chapter_id', $chapterIds)
                    ->orWhereHas('chapter', function ($q) use ($courseIds) {
                        $q->whereIn('course_id', $courseIds);
                    })
                    ->orWhere('price', 0);
            })

            ->whereHas('chapter.course', function ($q) use ($student) {
                $q->where('stage_id', $student->stage_id);
                $q->where('grade_id', $student->grade_id);
                if ($student->division_id) {
                    $q->where(function ($qq) use ($student) {
                        $qq->where('division_id', $student->division_id)
                            ->orWhereNull('division_id');
                    });
                }
            })
            ->when($subjectId, function ($query) use ($subjectId) {
                $query->whereHas('chapter.course', function ($q) use ($subjectId) {
                    $q->where('subject_id', $subjectId);
                });
            })
            ->get();

        return response()->json([
            'status' => true,
            'data' => LessonResource::collection($lessons->unique('id')),
        ]);
    }
}
