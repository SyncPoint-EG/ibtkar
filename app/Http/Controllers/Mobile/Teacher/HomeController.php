<?php

namespace App\Http\Controllers\Mobile\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttachmentResource;
use App\Http\Resources\ExamResource;
use App\Http\Resources\HomeworkResource;
use App\Http\Resources\LessonResource;
use App\Http\Resources\StudentResource;
use App\Http\Resources\TeacherStudentResource;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Homework;
use App\Models\HomeworkAttempt;
use App\Models\Lesson;
use App\Models\LessonAttachment;
use App\Models\Payment;
use App\Models\Student;

class HomeController extends Controller
{
    public function getExams()
    {
        $teacher = auth()->guard('teacher')->user();
        $exams = Exam::where(function ($query) use ($teacher) {
            $query->whereHas('lesson.chapter.course', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })->orWhere('teacher_id', $teacher->id);
        })->where('is_active', 1)->get();
        //        })->where('is_active', 1)->whereDate('start_date','>',now())->get();

        return ExamResource::collection($exams);
    }
    public function getExamStudents($id)
    {
        $exam = Exam::findOrFail($id);
        $attempts = ExamAttempt::query()->where('exam_id',$exam->id)->with('student')->get();
        return response()->json([
            'data' => $attempts,
            'success' => true,
        ]);

    }

    public function getHomeworks()
    {
        $teacher = auth()->guard('teacher')->user();
        $homework = Homework::whereHas('lesson.chapter.course', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->where('is_active', 1)->whereDate('due_date', '>', now())->get();

        return HomeworkResource::collection($homework);
    }
    public function getHomeworkStudents($id)
    {
        $homework = Homework::findOrFail($id);
        $attempts = HomeworkAttempt::query()->where('homework_id', $homework->id)->with('student')->get();
        return response()->json([
            'data' => $attempts,
            'success' => true,
        ]);
    }

    public function getAttachments()
    {
        $teacher = auth()->guard('teacher')->user();
        $attachments = LessonAttachment::whereHas('lesson.chapter.course', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->paginate(15);

        return AttachmentResource::collection($attachments);
    }

    public function getStudents()
    {
        $teacher = auth()->guard('teacher')->user();
        $courseIds = Course::where('teacher_id', $teacher->id)->pluck('id');
        $chapterIds = Chapter::whereIn('course_id', $courseIds)->pluck('id');
        $lessonIds = Lesson::whereIn('chapter_id', $chapterIds)->pluck('id');
        $studentsIds = Payment::whereIn('course_id', $courseIds)
            ->orWhereIn('chapter_id', $chapterIds)
            ->orWhereIn('lesson_id', $lessonIds)
            ->distinct()
            ->pluck('student_id');
        $students = Student::whereIn('id', $studentsIds)
            ->when(request('stage_id'), function ($q, $stage_id) {
                $q->where('stage_id', $stage_id);
            })
            ->when(request('grade_id'), function ($q, $grade_id) {
                $q->where('grade_id', $grade_id);
            })
            ->when(request('division_id'), function ($q, $division_id) {
                $q->where('division_id', $division_id);
            })
            ->get();

        return StudentResource::collection($students);

    }

    public function getStudent($studentId)
    {
        $student = Student::query()->findOrFail($studentId);

        return new TeacherStudentResource($student);
    }

    public function getLessons()
    {
        $teacher = auth()->guard('teacher')->user();
        $lessons = Lesson::whereHas('chapter.course', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
            if(request()->stage_id){
                $query->where('stage_id', request()->stage_id);
            }
            if(request()->grade_id){
                $query->where('grade_id', request()->grade_id);
            }
            if(request()->division_id){
                $query->where('division_id', request()->division_id);
            }
        })->get();

        return LessonResource::collection($lessons);
    }
    public function getGrades()
    {
        $teacher = auth()->guard('teacher')->user();
        $grades = $teacher->grades->unique('id')->pluck('name')->values();
        return response()->json(['data' => $grades], 200);
    }
}
