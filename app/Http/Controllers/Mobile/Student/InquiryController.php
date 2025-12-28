<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\InquiryResource;
use App\Http\Resources\SubjectResource;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Inquiry;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Subject;
use App\Models\GradePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class InquiryController extends Controller
{
    public function index()
    {
        $student = auth('student')->user();

        $inquiries = Inquiry::where('student_id', $student->id)
            ->with(['teacher', 'subject'])
            ->latest()
            ->get();

        return InquiryResource::collection($inquiries);
    }

    public function subjects(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => ['required', 'exists:teachers,id'],
        ]);

        $student = auth('student')->user();
        $courseIds = $this->getPurchasedCourseIds($student);

        $subjects = Subject::whereIn(
            'id',
            Course::whereIn('id', $courseIds)
                ->where('teacher_id', $validated['teacher_id'])
                ->pluck('subject_id')
        )->get();

        return SubjectResource::collection($subjects);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => ['required', 'exists:teachers,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'question' => ['required', 'string', 'max:2000'],
        ]);

        $student = auth('student')->user();
        $courseIds = $this->getPurchasedCourseIds($student);

        $hasCourse = Course::whereIn('id', $courseIds)
            ->where('teacher_id', $validated['teacher_id'])
            ->where('subject_id', $validated['subject_id'])
            ->exists();

        if (! $hasCourse) {
            return response()->json([
                'message' => 'You have not purchased this subject from this teacher.',
            ], 403);
        }

        $inquiry = Inquiry::create([
            'student_id' => $student->id,
            'teacher_id' => $validated['teacher_id'],
            'subject_id' => $validated['subject_id'],
            'question' => $validated['question'],
        ]);

        $inquiry->load(['teacher', 'subject']);

        return new InquiryResource($inquiry);
    }

    private function getPurchasedCourseIds($student): Collection
    {
        $payments = Payment::where('student_id', $student->id)
            ->where('payment_status', Payment::PAYMENT_STATUS['approved'])
            ->get(['lesson_id', 'chapter_id', 'course_id']);

        $courseIds = $payments->whereNotNull('course_id')->pluck('course_id');

        $chapterIds = $payments->whereNotNull('chapter_id')->pluck('chapter_id');
        $chapterCourseIds = $chapterIds->isEmpty()
            ? collect()
            : Chapter::whereIn('id', $chapterIds)->pluck('course_id');

        $lessonIds = $payments->whereNotNull('lesson_id')->pluck('lesson_id');
        $lessonCourseIds = collect();
        if ($lessonIds->isNotEmpty()) {
            $lessonChapterIds = Lesson::whereIn('id', $lessonIds)->pluck('chapter_id');
            $lessonCourseIds = Chapter::whereIn('id', $lessonChapterIds)->pluck('course_id');
        }

        $gradePlanCourseIds = collect();
        $hasGradePlan = Payment::where('student_id', $student->id)
            ->where('plan_type', GradePlan::TYPE_GENERAL)
            ->where('payment_status', Payment::PAYMENT_STATUS['approved'])
            ->exists();

        if ($hasGradePlan) {
            $gradePlanCourseIds = Course::where('stage_id', $student->stage_id)
                ->where('grade_id', $student->grade_id)
                ->when($student->division_id, function ($query) use ($student) {
                    $query->where(function ($qq) use ($student) {
                        $qq->where('division_id', $student->division_id)
                            ->orWhereNull('division_id');
                    });
                })
                ->pluck('id');
        }

        return $courseIds
            ->merge($chapterCourseIds)
            ->merge($lessonCourseIds)
            ->merge($gradePlanCourseIds)
            ->unique()
            ->values();
    }
}
