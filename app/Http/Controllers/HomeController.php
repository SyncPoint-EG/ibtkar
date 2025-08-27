<?php

namespace App\Http\Controllers;

use App\Http\Resources\CentersResource;
use App\Http\Resources\DivisionsResource;
use App\Http\Resources\EducationTypeResource;
use App\Http\Resources\GovernoratesResource;
use App\Http\Resources\GradesResource;
use App\Http\Resources\StagesResource;
use App\Models\Center;
use App\Models\District;
use App\Models\Division;
use App\Models\EducationType;
use App\Models\Governorate;
use App\Models\Grade;
use App\Models\Stage;
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

}
