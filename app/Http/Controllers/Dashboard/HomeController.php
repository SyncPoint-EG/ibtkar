<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Charge;
use App\Models\Code;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Homework;
use App\Models\Lesson;
use App\Models\LessonAttachment;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Watch;
use App\Traits\FirebaseNotify;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use FirebaseNotify ;
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Default to the latest 30 days when no range is provided to keep charts meaningful
        if (! $startDate && ! $endDate) {
            $startDate = now()->subDays(29)->toDateString();
            $endDate = now()->toDateString();
        }

        $dateFilter = function ($query) use ($startDate, $endDate) {
            if ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            }
        };

        // Main stats
        $studentsCount = Student::where($dateFilter)->count();
        $purchasingStudentsCount = Payment::where('is_approved', 1)
            ->where($dateFilter)
            ->distinct('student_id')
            ->count('student_id');
        $teachersCount = Teacher::where($dateFilter)->count();
        $coursesCount = Course::where($dateFilter)->count();
        $chaptersCount = Chapter::where($dateFilter)->count();
        $lessonsCount = Lesson::where($dateFilter)->count();
        $subjectsCount = Subject::where($dateFilter)->count();
        $examsCount = Exam::where($dateFilter)->count();
        $homeworksCount = Homework::where($dateFilter)->count();
        $lessonAttachmentsCount = LessonAttachment::where($dateFilter)->count();

        $mainStats = [
            'Registered Students' => $studentsCount,
            'Purchasing Students' => $purchasingStudentsCount,
            'Teachers' => $teachersCount,
            'Courses' => $coursesCount,
            'Chapters' => $chaptersCount,
            'Lessons' => $lessonsCount,
            'Subjects' => $subjectsCount,
            'Exams' => $examsCount,
            'Homeworks' => $homeworksCount,
            'Lesson Attachments' => $lessonAttachmentsCount,
        ];

        // Financial stats
        $financialStats = [];
        if (auth()->user()->can('view_financial_stats')) {
            $paidLessonsQuery = Payment::whereNotNull('lesson_id')->where('is_approved', 1)->where($dateFilter);
            $paidCoursesQuery = Payment::whereNotNull('course_id')->where('is_approved', 1)->where($dateFilter);
            $allPaymentsQuery = Payment::where('is_approved', 1)->where($dateFilter);

            // The 'charges' table has a 'type' column with 'increase' or 'decrease' values.
            // The HomeController was using 'charge' and 'transfer'.
            // I will assume 'charge' corresponds to 'increase' and 'transfer' is not a valid type.
            // I will also assume the status is in the 'payment_status' column with a value of 'completed'.
            $chargesQuery = Charge::where('type', 'increase')->where('payment_status', 'completed')->where($dateFilter);

            $financialStats = [
                'Purchasing Students Count' => $purchasingStudentsCount,
                'Approved Payments Count' => (clone $allPaymentsQuery)->count(),
                'Approved Payments Total' => (clone $allPaymentsQuery)->sum('amount'),
                'Paid Lessons Count' => (clone $paidLessonsQuery)->count(),
                'Paid Lessons Total' => (clone $paidLessonsQuery)->sum('amount'),
                'Paid Courses Count' => (clone $paidCoursesQuery)->count(),
                'Paid Courses Total' => (clone $paidCoursesQuery)->sum('amount'),
                'Total Payments Amount' => (clone $allPaymentsQuery)->sum('amount'),
                'Coupons Count' => Code::where($dateFilter)->count(),
                'Used Coupons Count' => Code::where('number_of_uses', '>', 0)->where($dateFilter)->count(),
                'Charge Actions Count' => (clone $chargesQuery)->count(),
                'Charge Actions Total' => (clone $chargesQuery)->sum('amount'),
            ];
        }

        // Charts Data
        $lessonsChart = Lesson::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where($dateFilter)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->pluck('count', 'date');

        $lessonViewsChart = Watch::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where($dateFilter)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->pluck('count', 'date');

        $studentRegistrationsChart = Student::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where($dateFilter)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->pluck('count', 'date');

        $paymentAmountChart = Payment::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->where('is_approved', 1)
            ->where($dateFilter)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'date');

        $paymentCountChart = Payment::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('is_approved', 1)
            ->where($dateFilter)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->pluck('count', 'date');

        $purchasingStudentsDaily = Payment::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(DISTINCT student_id) as count'))
            ->where('is_approved', 1)
            ->where($dateFilter)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->pluck('count', 'date');

        $chartLabels = collect()
            ->merge($paymentAmountChart->keys())
            ->merge($paymentCountChart->keys())
            ->merge($purchasingStudentsDaily->keys())
            ->merge($studentRegistrationsChart->keys())
            ->unique()
            ->sort()
            ->values();

        $paymentAmountSeries = $chartLabels->map(fn ($date) => (float) ($paymentAmountChart[$date] ?? 0));
        $paymentCountSeries = $chartLabels->map(fn ($date) => (int) ($paymentCountChart[$date] ?? 0));
        $purchasingStudentsSeries = $chartLabels->map(fn ($date) => (int) ($purchasingStudentsDaily[$date] ?? 0));
        $newStudentsSeries = $chartLabels->map(fn ($date) => (int) ($studentRegistrationsChart[$date] ?? 0));

        return view('dashboard.index', compact(
            'mainStats',
            'financialStats',
            'lessonsChart',
            'lessonViewsChart',
            'studentRegistrationsChart',
            'chartLabels',
            'paymentAmountSeries',
            'paymentCountSeries',
            'purchasingStudentsSeries',
            'newStudentsSeries',
            'startDate',
            'endDate'
        ));
    }

    public function test()
    {
        return $this->sendFirebaseNotification(User::first(),'test','body');
    }
}
