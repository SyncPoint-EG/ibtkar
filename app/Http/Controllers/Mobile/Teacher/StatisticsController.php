<?php

namespace App\Http\Controllers\Mobile\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Homework;
use App\Models\Lesson;
use App\Models\LessonAttachment;
use App\Models\Payment;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index()
    {
        $teacher = auth()->user();

        $courses = Course::where('teacher_id', $teacher->id)->pluck('id');
        $chapters = \App\Models\Chapter::whereIn('course_id', $courses)->pluck('id');
        $lessons = Lesson::whereIn('chapter_id', $chapters);

        $lessonsCount = $lessons->count();
        $examsCount = Exam::whereIn('lesson_id', $lessons->pluck('id'))->count();
        $homeworksCount = Homework::whereIn('lesson_id', $lessons->pluck('id'))->count();
        $attachmentsCount = LessonAttachment::whereIn('lesson_id', $lessons->pluck('id'))->count();

        $studentsCount = Payment::where(function ($query) use ($courses, $chapters, $lessons) {
            $query->whereIn('course_id', $courses)
                ->orWhereIn('chapter_id', $chapters)
                ->orWhereIn('lesson_id', $lessons->pluck('id'));
        })->distinct('student_id')->count();

        return response()->json([
            'lessons_count' => $lessonsCount,
            'exams_count' => $examsCount,
            'homeworks_count' => $homeworksCount,
            'attachments_count' => $attachmentsCount,
            'students_count' => $studentsCount,
        ]);
    }
}
