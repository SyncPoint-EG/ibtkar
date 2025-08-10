<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStudentProfileRequest;
use App\Http\Resources\StudentResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return new StudentResource($request->user());
    }

    public function update(UpdateStudentProfileRequest $request)
    {
        $student = $request->user();
        $student->update($request->validated());

        return new StudentResource($student);
    }
}