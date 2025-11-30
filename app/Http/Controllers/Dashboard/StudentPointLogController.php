<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentPointLogController extends Controller
{
    public function index()
    {
        $students = Student::query()
            ->withSum('pointLogs', 'points')
            ->withCount('pointLogs')
            ->where(function ($query) {
                $query->has('pointLogs')
                    ->orWhere('points', '>', 0);
            })
            ->orderByDesc(DB::raw('COALESCE(point_logs_sum_points, 0) + points'))
            ->paginate(15);

        return view('dashboard.points-logs.index', compact('students'));
    }

    public function show(Student $student)
    {
        $logs = $student->pointLogs()->latest()->paginate(25);
        $totalPoints = $student->pointLogs()->sum('points');

        return view('dashboard.points-logs.show', compact('student', 'logs', 'totalPoints'));
    }
}
