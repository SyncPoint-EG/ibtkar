<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentPointLogController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['name', 'phone', 'action_name']);

        $students = Student::query()
            ->withSum('pointLogs', 'points')
            ->withCount('pointLogs')
            ->where(function ($query) {
                $query->has('pointLogs')
                    ->orWhere('points', '>', 0);
            })
            ->when($filters['name'] ?? null, function ($query, $name) {
                $query->where(function ($q) use ($name) {
                    $q->where('first_name', 'like', "%{$name}%")
                        ->orWhere('last_name', 'like', "%{$name}%")
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$name}%");
                });
            })
            ->when($filters['phone'] ?? null, function ($query, $phone) {
                $query->where('phone', 'like', "%{$phone}%");
            })
            ->when($filters['action_name'] ?? null, function ($query, $action) {
                $query->whereHas('pointLogs', function ($logQuery) use ($action) {
                    $logQuery->where('action_name', 'like', "%{$action}%");
                });
            })
            ->orderByDesc(DB::raw('COALESCE(point_logs_sum_points, 0) + points'))
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.points-logs.index', [
            'students' => $students,
            'filters' => $filters,
        ]);
    }

    public function show(Student $student)
    {
        $logs = $student->pointLogs()->latest()->paginate(25);
        $totalPoints = $student->pointLogs()->sum('points');

        return view('dashboard.points-logs.show', compact('student', 'logs', 'totalPoints'));
    }
}
