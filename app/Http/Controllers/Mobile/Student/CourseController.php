<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;

class CourseController extends Controller
{
    protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index()
    {
        $student = auth('student')->user();
        $courses = Course::where('stage_id', $student->stage_id)
            ->where('grade_id', $student->grade_id)
            ->where('division_id', $student->division_id)
            ->get();

        return CourseResource::collection($courses);
    }

    public function show(Course $course)
    {
        return new CourseResource($course->load(['teacher', 'subject', 'chapters.lessons']));
    }
}
