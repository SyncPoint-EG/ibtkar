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
        $filterApplied = $startDate || $endDate;

        $dateFilter = function ($query) use ($startDate, $endDate, $filterApplied) {
            if (! $filterApplied) {
                return;
            }

            if ($startDate != null) {
                $query->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate != null) {
                $query->whereDate('created_at', '<=', $endDate);
            }
        };

        // Main stats
        $studentsCount = Student::where($dateFilter)->count();
        $approvedPaymentsBaseQuery = Payment::approved()->where($dateFilter);
        $purchasingStudentsCount = (clone $approvedPaymentsBaseQuery)
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
            $paidLessonsQuery = (clone $approvedPaymentsBaseQuery)->whereNotNull('lesson_id');
            $paidCoursesQuery = (clone $approvedPaymentsBaseQuery)->whereNotNull('course_id');
            $allPaymentsQuery = clone $approvedPaymentsBaseQuery;

            $chargeStatuses = array_unique([
                Payment::PAYMENT_STATUS['approved'],
                Payment::PAYMENT_STATUS['accepted'],
                'completed',
                'Completed',
            ]);

            $chargesQuery = Charge::where('type', 'increase')
                ->whereIn('payment_status', $chargeStatuses)
                ->where($dateFilter);

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
            ->approved()
            ->where($dateFilter)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'date');

        $paymentCountChart = Payment::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->approved()
            ->where($dateFilter)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->pluck('count', 'date');

        $purchasingStudentsDaily = Payment::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(DISTINCT student_id) as count'))
            ->approved()
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
