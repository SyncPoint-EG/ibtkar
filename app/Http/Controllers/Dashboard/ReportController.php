<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Code;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function students()
    {
        $students = Student::withCount('watches')
            ->with(['division', 'guardian', 'district.governorate'])
            ->paginate(15);

        return view('dashboard.reports.students', compact('students'));
    }

    public function teachers()
    {
        $last_lecture_date = DB::table('lessons')
            ->select('lessons.created_at')
            ->join('chapters', 'lessons.chapter_id', '=', 'chapters.id')
            ->join('courses', 'chapters.course_id', '=', 'courses.id')
            ->whereColumn('courses.teacher_id', 'teachers.id')
            ->latest('lessons.created_at')
            ->limit(1);

        $students_count = DB::table('payments')->selectRaw('count(distinct student_id)')
            ->where(function ($query) {
                $query->whereIn('course_id', function ($subQuery) {
                    $subQuery->select('id')->from('courses')->whereColumn('teacher_id', 'teachers.id');
                })
                    ->orWhereIn('chapter_id', function ($subQuery) {
                        $subQuery->select('id')->from('chapters')->whereIn('course_id', function ($subQuery2) {
                            $subQuery2->select('id')->from('courses')->whereColumn('teacher_id', 'teachers.id');
                        });
                    })
                    ->orWhereIn('lesson_id', function ($subQuery) {
                        $subQuery->select('id')->from('lessons')->whereIn('chapter_id', function ($subQuery2) {
                            $subQuery2->select('id')->from('chapters')->whereIn('course_id', function ($subQuery3) {
                                $subQuery3->select('id')->from('courses')->whereColumn('teacher_id', 'teachers.id');
                            });
                        });
                    });
            });

        $lessons_count = DB::table('lessons')->whereIn('chapter_id', function ($query) {
            $query->select('id')->from('chapters')->whereIn('course_id', function ($query2) {
                $query2->select('id')->from('courses')->whereColumn('teacher_id', 'teachers.id');
            });
        })->selectRaw('count(*)');

        $teachers = Teacher::with('subjects')
            ->addSelect([
                'last_lecture_date' => $last_lecture_date,
                'students_count' => $students_count,
                'lessons_count' => $lessons_count,
            ])
            ->paginate(15);

        return view('dashboard.reports.teachers', compact('teachers'));
    }

    public function payments()
    {
        $payments = Payment::with(['student', 'course.teacher', 'chapter.course.teacher', 'lesson.chapter.course.teacher'])
            ->paginate(15);

        return view('dashboard.reports.payments', compact('payments'));
    }

    public function codes()
    {
        $codes = Code::with(['teacher', 'payment.student'])
            ->paginate(15);

        return view('dashboard.reports.codes', compact('codes'));
    }
}
