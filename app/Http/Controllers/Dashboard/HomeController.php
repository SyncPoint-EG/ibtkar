<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
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
use App\Models\Charge;
use App\Models\Semister;
use App\Models\Watch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

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
        $teachersCount = Teacher::where($dateFilter)->count();
        $coursesCount = Course::where($dateFilter)->count();
        $chaptersCount = Chapter::where($dateFilter)->count();
        $lessonsCount = Lesson::where($dateFilter)->count();
        $subjectsCount = Subject::where($dateFilter)->count();
        $examsCount = Exam::where($dateFilter)->count();
        $homeworksCount = Homework::where($dateFilter)->count();
        $lessonAttachmentsCount = LessonAttachment::where($dateFilter)->count();

        $mainStats = [
            'Students' => $studentsCount,
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
            $paidLessonsQuery = Payment::where('paymentable_type', 'App\\Models\\Lesson')->where('status', 'paid')->where($dateFilter);
            $paidCoursesQuery = Payment::where('paymentable_type', 'App\\Models\\Course')->where('status', 'paid')->where($dateFilter);
            $paidSemistersQuery = Payment::where('paymentable_type', 'App\\Models\\Semister')->where('status', 'paid')->where($dateFilter);
            // Assuming 'App\\Models\\Subscription' is a paymentable type for subscriptions.
            $subscriptionsQuery = Payment::where('paymentable_type', 'App\\Models\\Subscription')->where('status', 'paid')->where($dateFilter);
            $allPaymentsQuery = Payment::where('status', 'paid')->where($dateFilter);

            // Assuming 'type' column in charges table to distinguish between charge and transfer
            $chargesQuery = Charge::where('type', 'charge')->where('status', 'completed')->where($dateFilter);
            $transfersQuery = Charge::where('type', 'transfer')->where('status', 'completed')->where($dateFilter);

            $financialStats = [
                'Paid Lessons Count' => (clone $paidLessonsQuery)->count(),
                'Paid Lessons Total' => (clone $paidLessonsQuery)->sum('amount'),
                'Subscriptions Count' => (clone $subscriptionsQuery)->count(),
                'Subscriptions Total' => (clone $subscriptionsQuery)->sum('amount'),
                'Paid Courses Count' => (clone $paidCoursesQuery)->count(),
                'Paid Courses Total' => (clone $paidCoursesQuery)->sum('amount'),
                'Paid Terms Count' => (clone $paidSemistersQuery)->count(),
                'Paid Terms Total' => (clone $paidSemistersQuery)->sum('amount'),
                'Total Payments Amount' => $allPaymentsQuery->sum('amount'),
                'Coupons Count' => Code::where($dateFilter)->count(),
                'Used Coupons Count' => Code::where('is_used', true)->where($dateFilter)->count(),
                'Charge Actions Count' => (clone $chargesQuery)->count(),
                'Charge Actions Total' => (clone $chargesQuery)->sum('amount'),
                'Transfer Actions Count' => (clone $transfersQuery)->count(),
                'Transfer Actions Total' => (clone $transfersQuery)->sum('amount'),
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

        return view('dashboard.index', compact('mainStats', 'financialStats', 'lessonsChart', 'lessonViewsChart', 'startDate', 'endDate'));
    }
}
