<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\DivisionResource;
use App\Http\Resources\GradesResource;
use App\Http\Resources\LessonResource;
use App\Http\Resources\StagesResource;
use App\Http\Resources\SubjectResource;
use App\Models\Banner;
use App\Models\Course;
use App\Models\Division;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Stage;
use App\Models\Subject;
use App\Services\BannerService;
use App\Services\SubjectService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected SubjectService $subjectService;
    protected BannerService $bannerService;

    public function __construct(SubjectService $subjectService , BannerService $bannerService)
    {
        $this->subjectService = $subjectService;
        $this->bannerService = $bannerService;

    }
    public function getBanners()
    {
        $banners = $this->bannerService->getAllPaginated();
        return BannerResource::collection($banners);
    }
    public function getSubjects()
    {
        $subjects = $this->subjectService->getAll();
        return SubjectResource::collection($subjects);
    }
    public function getSubject(Subject $subject)
    {
        return new SubjectResource($subject);
    }
    public function getDivisions()
    {
        $divisions = Division::all();
        return DivisionResource::collection($divisions);
    }
    public function getStages()
    {
        $stages = Stage::all();
        return StagesResource::collection($stages);
    }
    public function getGrades()
    {
        $grades = Grade::all();
        return GradesResource::collection($grades);
    }

    public function timeline(Request $request)
    {
        $student = $request->user();
        $date = $request->input('date', Carbon::today()->toDateString());

        $lessons = Lesson::where('date', $date)
            ->whereHas('chapter.course',function ($q) use ($student){
                $q->where('stage_id', $student->stage_id)
                    ->where('grade_id', $student->grade_id)
                    ->where('division_id', $student->division_id);
            })->get();

        return LessonResource::collection($lessons);
    }
}
