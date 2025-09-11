<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function getTeacherTables(Request $request): JsonResponse
    {
        $student = $request->user();

        $query = Teacher::whereHas('courses', function ($q) use ($student) {
            $q->where('stage_id', $student->stage_id)
              ->where('grade_id', $student->grade_id)
              ->where('division_id', $student->division_id);
        });

        if ($request->has('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        if ($request->has('time')) {
            $query->where('time', $request->time);
        }

        $teachers = $query->orderBy('day_of_week')->orderBy('time')->get();

        $grouped = $teachers->groupBy('day_of_week')->map(function ($day) {
            return $day->groupBy('time');
        });

        return response()->json(['data' => $grouped]);
    }
}
