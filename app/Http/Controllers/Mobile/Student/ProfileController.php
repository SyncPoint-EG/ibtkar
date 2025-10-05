<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStudentProfileRequest;
use App\Http\Resources\StudentProfileResource;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return new StudentProfileResource(auth('student')->user());
    }

    public function update(UpdateStudentProfileRequest $request)
    {
        $student = $request->user();
        $student->update($request->validated());

        return new StudentResource($student);
    }

    public function updateImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);
        $student = auth('student')->user();
        $student->image = $request->image;
        $student->save();

        return new StudentResource($student);
    }

    public function deleteAccount(Request $request)
    {
        $student = auth('student')->user();
        $student->delete();

        return response()->json([
            'message' => 'Successfully deleted account!',
            'success' => true,
        ]);
    }

    public function studentsByPoints()
    {
        $student = auth('student')->user();

        $students = Student::where('id', '!=', $student->id)
            ->where('stage_id', $student->stage_id)
            ->where('grade_id', $student->grade_id)
            ->where('division_id', $student->division_id)
            ->orderBy('points', 'DESC')
            ->select('id', 'first_name', 'last_name', 'image', 'points')
            ->get();

        return response()->json([
            'students' => $students,
        ]);

    }
}
