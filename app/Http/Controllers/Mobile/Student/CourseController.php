<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }
    public function index()
    {
        $courses = $this->courseService->getAllPaginated();
        return CourseResource::collection($courses);
    }

    public function show(Course $course)
    {
        return new CourseResource($course->load(['teacher', 'subject', 'chapters.lessons']));
    }
}
