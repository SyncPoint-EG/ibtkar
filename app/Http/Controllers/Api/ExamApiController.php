<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Division;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Stage;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamApiController extends Controller
{
    public function getTeacherCourses(Teacher $teacher): JsonResponse
    {
        return response()->json($teacher->courses);
    }

    public function getStages(): JsonResponse
    {
        return response()->json(Stage::all());
    }

    public function getGradesByStage($stageId): JsonResponse
    {
        $stage = Stage::findOrFail($stageId);
        return response()->json($stage->grades);
    }

    public function getDivisionsByGrade($stageId, $gradeId): JsonResponse
    {
        $grade = Grade::findOrFail($gradeId);
        $stage = Stage::findOrFail($stageId);
        $divisions = Division::query()->where('grade_id', $grade->id)
            ->where('stage_id', $stage->id)
            ->get();
        return response()->json($divisions);
    }

    public function getCoursesByFilters(Request $request): JsonResponse
    {
        $courses = Course::query()
            ->when($request->stage_id, fn($q, $v) => $q->where('stage_id', $v))
            ->when($request->grade_id, fn($q, $v) => $q->where('grade_id', $v))
            ->when($request->division_id, fn($q, $v) => $q->where('division_id', $v))
            ->get();

        return response()->json($courses);
    }

    public function getChaptersByCourse(Course $course): JsonResponse
    {
        return response()->json($course->chapters);
    }

    public function getLessonsByChapter( $chapterId): JsonResponse
    {
        $lessons = Lesson::where('chapter_id', $chapterId)->get();
        return response()->json($lessons);
    }
}
