<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubjectTeacherResource;
use App\Models\SubjectTeacher;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function getGeneralTeacherTables(Request $request)
    {
        $student = auth()->guard('student')->user();

        $query = SubjectTeacher::with('teacher')->where('stage_id', $student->stage_id)
            ->where('grade_id', $student->grade_id);

        if ($student->division_id) {
            $query->where(function ($qq) use ($student) {
                $qq->where('division_id', $student->division_id)
                    ->orWhereNull('division_id');
            });
        }

        if ($request->has('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        if ($request->has('time')) {
            $query->where('time', $request->time);
        }

        $subjectTeachers = $query->orderBy('day_of_week')->orderBy('time')->get();

        return SubjectTeacherResource::collection($subjectTeachers);
    }

    public function getPrivateTable(Request $request)
    {
        $student = auth()->guard('student')->user();
        $query = SubjectTeacher::with('teacher')->where('stage_id', $student->stage_id)
            ->where('grade_id', $student->grade_id)
            ->whereHas('teacher.courses', function ($q) {
                $q->where(function ($q) {
                    $q->whereHas('payments')
                        ->orWhereHas('chapters.payments')
                        ->orWhereHas('chapters.lessons.payments');
                });
            });

        if ($student->division_id) {
            $query->where(function ($qq) use ($student) {
                $qq->where('division_id', $student->division_id)
                    ->orWhereNull('division_id');
            });
        }

        if ($request->has('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        if ($request->has('time')) {
            $query->where('time', $request->time);
        }

        $subjectTeachers = $query->orderBy('day_of_week')->orderBy('time')->get();

        return SubjectTeacherResource::collection($subjectTeachers);
    }
}
