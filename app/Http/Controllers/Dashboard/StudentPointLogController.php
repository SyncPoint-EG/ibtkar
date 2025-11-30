<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentPointLogController extends Controller
{
    public function index()
    {
        $students = Student::whereHas('pointLogs')
            ->withSum('pointLogs', 'points')
            ->withCount('pointLogs')
            ->orderByDesc('point_logs_sum_points')
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
