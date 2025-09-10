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
        $query = Teacher::query();

        if ($request->has('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        if ($request->has('time')) {
            $query->where('time', $request->time);
        }

        $teachers = $query->get();

        return response()->json(['data' => $teachers]);
    }
}
