<?php

namespace App\Http\Resources;

use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Watch;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TeacherStudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\Student $student */
        $student = $this->resource;

        /** @var \App\Models\Teacher $teacher */
        $teacher = Auth::guard('teacher')->user();

        // Base student data - assuming fields from a StudentResource/StudentProfileResource
        $studentData = [
            'id' => $student->id,
            'name' => $student->name,
            'email' => $student->email,
            'mobile' => $student->mobile,
            'stage' => $student->stage->name ?? null,
            'grade' => $student->grade->name ?? null,
            'division' => $student->division->name ?? null,
        ];

        // --- Exams Statistics (Optimized to avoid N+1 queries) ---

        $examIds = Exam::where('teacher_id', $teacher->id)
            ->where('stage_id', $student->stage_id)
            ->where('grade_id', $student->grade_id)
            ->where('division_id', $student->division_id)
            ->pluck('id');

        $exams = Exam::whereIn('id', $examIds)->get();

        $attempts = ExamAttempt::where('student_id', $student->id)
            ->whereIn('exam_id', $examIds)
            ->get()
            ->keyBy('exam_id');

        $examData = $exams->map(function ($exam) use ($attempts) {
            $attempt = $attempts->get($exam->id);

            return [
                'id' => $exam->id,
                'name' => $exam->name,
                'student_degree' => $attempt ? $attempt->degree : null,
                'total_degree' => $exam->total_degree,
            ];
        });

        // --- Lessons Status (Optimized to avoid N+1 queries) ---

        $courseIds = Course::where('teacher_id', $teacher->id)->pluck('id');
        $teacherLessons = Lesson::whereHas('chapter', function ($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds);
        })->with('chapter')->get();
        $teacherLessonIds = $teacherLessons->pluck('id');

        $payments = Payment::where('student_id', $student->id)
            ->where(function ($query) use ($teacherLessonIds, $teacherLessons) {
                $query->whereIn('lesson_id', $teacherLessonIds)
                    ->orWhereIn('chapter_id', $teacherLessons->pluck('chapter_id')->unique())
                    ->orWhereIn('course_id', $teacherLessons->pluck('chapter.course_id')->unique());
            })
            ->get();

        $purchasedLessonIds = $payments->whereNotNull('lesson_id')->pluck('lesson_id');
        $purchasedChapterIds = $payments->whereNotNull('chapter_id')->pluck('chapter_id');
        $purchasedCourseIds = $payments->whereNotNull('course_id')->pluck('course_id');

        $watchedLessonIds = Watch::where('student_id', $student->id)
            ->whereIn('lesson_id', $teacherLessonIds)
            ->pluck('lesson_id');

        $lessonData = $teacherLessons->map(function ($lesson) use ($purchasedLessonIds, $purchasedChapterIds, $purchasedCourseIds, $watchedLessonIds) {
            $isPurchased = $purchasedLessonIds->contains($lesson->id)
                || $purchasedChapterIds->contains($lesson->chapter_id)
                || $purchasedCourseIds->contains($lesson->chapter->course_id);

            $isWatched = $watchedLessonIds->contains($lesson->id);

            return [
                'id' => $lesson->id,
                'name' => $lesson->name,
                'is_purchased_and_watched' => $isPurchased && $isWatched,
            ];
        });

        return array_merge($studentData, [
            'exams' => $examData,
            'lessons' => $lessonData,
        ]);
    }
}
