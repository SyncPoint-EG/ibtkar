<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function getLessons()
    {
        $perPage = \request()->query('perPage',10);
        $lessons = Lesson::where('is_featured',1)->query()->paginate($perPage);
        return LessonResource::collection($lessons);
    }
    public function getLesson(Lesson $lesson)
    {
        return new LessonResource($lesson);
    }
}
