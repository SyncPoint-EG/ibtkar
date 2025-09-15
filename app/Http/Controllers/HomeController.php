<?php

namespace App\Http\Controllers;

use App\Http\Resources\CentersResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\DivisionsResource;
use App\Http\Resources\EducationTypeResource;
use App\Http\Resources\GovernoratesResource;
use App\Http\Resources\GradesResource;
use App\Http\Resources\LessonAttachmentResource;
use App\Http\Resources\StagesResource;
use App\Http\Resources\TeacherResource;
use App\Models\Center;
use App\Models\Course;
use App\Models\District;
use App\Models\Division;
use App\Models\EducationType;
use App\Models\Governorate;
use App\Models\Grade;
use App\Models\LessonAttachment;
use App\Models\Stage;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function switchLanguage($locale)
    {
        // Check if the locale is supported
        if (in_array($locale, ['en', 'ar'])) {
            // Store the locale in session
            Session::put('locale', $locale);

            // Set the application locale
            App::setLocale($locale);
        }

        // Redirect back to the previous page
        return redirect()->back();
    }

    public function getStages(){
        $stages = Stage::all();
        return StagesResource::collection($stages);
    }

    public function getGrades($id)
    {
        $grades = Grade::where('stage_id', $id)->get();
        return GradesResource::collection($grades);
    }
    public function getDivisions(Stage $stage, Grade $grade)
    {
        $divisions = Division::where('stage_id', $stage->id)
            ->where('grade_id', $grade->id)
            ->get();
        return DivisionsResource::collection($divisions);
    }

    public function getCenters()
    {
        $centers = Center::all();
        return CentersResource::collection($centers);
    }
    public function getEducationTypes()
    {
        $educationTypes =EducationType::all();
        return EducationTypeResource::collection($educationTypes);
    }

    public function getGovernorates()
    {
        $governorates = Governorate::all();
        return GovernoratesResource::collection($governorates);
    }
    public function getDistricts(Governorate $governorate)
    {
        $districts = District::where('governorate_id', $governorate->id)->get();
        return response()->json($districts);
    }

    public function getCourses()
    {
        $courses = Course::query()->where('is_featured',1);
        if(request()->stage_id){
            $courses = $courses->where('stage_id', request()->stage_id);
        }
        if(request()->grade_id){
            $courses = $courses->where('grade_id', request()->grade_id);
        }
        if(request()->division_id){
            $courses = $courses->where('division_id', request()->division_id);
        }
        if(request()->subject_id){
            $courses = $courses->where('subject_id', request()->subject_id);
        }
        $courses = $courses->latest()->get();
        return CourseResource::collection($courses);
    }

    public function getTeachers()
    {
        $teachers = Teacher::query()->paginate(request()->perpage ?? 6);
        return TeacherResource::collection($teachers);

    }

    public function getAttachments()
    {
        $attachments = LessonAttachment::query()->where('is_featured',1)
            ->whereHas('lesson.chapter.course', function ($query) {
                $query->when(request('stage_id'), function ($q, $stageId) {
                    $q->where('stage_id', $stageId);
                })
                    ->when(request('grade_id'), function ($q, $gradeId) {
                        $q->where('grade_id', $gradeId);
                    })
                    ->when(request('division_id'), function ($q, $divisionId) {
                        $q->where('division_id', $divisionId);
                    })
                    ->when(request('subject_id'), function ($q, $subjectId) {
                        $q->where('subject_id', $subjectId);
                    });
            })
            ->paginate(request('perpage', 6));

        return LessonAttachmentResource::collection($attachments);
    }

}
