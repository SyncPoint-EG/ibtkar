<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Resources\SimpleTeacherResource;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function getGeneralTeacherTables(Request $request)
    {
        $student = auth()->guard('student')->user();

        $query = Teacher::whereHas('courses', function ($q) use ($student) {
            $q->where('stage_id', $student->stage_id);
              $q->where('grade_id', $student->grade_id);
            if($student->division_id){
                $q->where(function ($qq) use ($student) {
                    $qq->where('division_id', $student->division_id)
                        ->orWhereNull('division_id');
                });
            }
        });

        if ($request->has('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        if ($request->has('time')) {
            $query->where('time', $request->time);
        }

        $teachers = $query->orderBy('day_of_week')->orderBy('time')->get();

//        $grouped = $teachers->groupBy('day_of_week')->map(function ($day) {
//            return $day->groupBy('time');
//        });

        return SimpleTeacherResource::collection($teachers);
    }

    public function getPrivateTable(Request $request)
    {
        $student = auth()->guard('student')->user();
        $query = Teacher::query()->whereHas('courses', function ($q) use ($student) {
            $q->where('stage_id', $student->stage_id)
                ->where('grade_id', $student->grade_id)
                ->where('division_id', $student->division_id)
                ->where(function ($q) {
                    $q->whereHas('payments')
                        ->orWhereHas('chapters.payments')
                        ->orWhereHas('chapters.lessons.payments');
                });
        });

        if ($request->has('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        if ($request->has('time')) {
            $query->where('time', $request->time);
        }

        $teachers = $query->orderBy('day_of_week')->orderBy('time')->get();

//        $grouped = $teachers->groupBy('day_of_week')->map(function ($day) {
//            return $day->groupBy('time');
//        });

        return SimpleTeacherResource::collection($teachers);
    }
}
