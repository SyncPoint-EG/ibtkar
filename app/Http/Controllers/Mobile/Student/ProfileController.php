<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStudentProfileRequest;
use App\Http\Resources\StudentProfileResource;
use App\Http\Resources\StudentResource;
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
}
