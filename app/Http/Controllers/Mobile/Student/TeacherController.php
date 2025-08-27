<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\SingleTeacherResource;
use App\Http\Resources\TeacherResource;
use App\Models\Division;
use App\Models\Grade;
use App\Models\Stage;
use App\Models\Student;
use App\Models\Subject;
use App\Services\TeacherService;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeachersExport;
use PDF;

class TeacherController extends Controller
{
    protected TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }


    public function index(Request $request)
    {
        $sort_by       = $request->get('sort_by', 'id');
        $sort_direction= $request->get('sort_direction', 'asc');
        $per_page      = $request->get('per_page', 10);
        // Build query with eager loading of assignments and related models
        $query = Teacher::with([
            'courses.subject',
            'courses.grade',
            'courses.stage',
            'courses.division'
        ]);

//            ->withCount(['students', 'lectures', 'courses']);

        $query = $this->filter($query ,$request);


        // Sorting
        $query->orderBy($sort_by, $sort_direction);

        // Paginate results
        $teachers = $query->paginate($per_page)->withQueryString();



        return TeacherResource::collection($teachers);
    }




    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->load(['subjects', 'grades', 'stages', 'divisions']);
        return new SingleTeacherResource($teacher);
    }

    public function timeline(Teacher $teacher)
    {
        $courses = $teacher->courses()->with(['chapters.lessons', 'stage', 'grade'])->get();

        $timeline = $courses->groupBy(function ($course) {
            return $course->stage->name . ' - ' . $course->grade->name;
        });

        return response()->json($timeline);
    }

    public function lessonsBySubject(Teacher $teacher)
    {
        $courses = $teacher->courses()->with(['chapters.lessons', 'subject'])
            ->whereHas('chapters.lessons',function ($q){
                $q->whereDate('date','>=',now());
            })->get();

        $lessonsBySubject = $courses->groupBy(function ($course) {
            return $course->subject->name;
        });

        return CourseResource::collection($lessonsBySubject);
    }

    public function filter($query , $request)
    {
        // Get filter parameters
        $search        = $request->get('search');
        $status        = $request->get('status');
        $rate          = $request->get('rate');
        $stage_id      = $request->get('stage_id');
        $subject_id    = $request->get('subject_id');
        $grade_id      = $request->get('grade_id'); // Optional
        $division_id   = $request->get('division_id'); // Optional
        $is_featured   = $request->get('is_featured'); // to display in home screen


        // Search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('other_phone', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        // rate  filter
        if ($rate !== null && $rate !== '') {
            $query->where('rate','<=', $rate);
        }

        // Filter by subject_teacher pivot assignments
        if ($subject_id || $stage_id || $grade_id || $division_id) {
            $query->whereHas('courses', function ($q) use ($subject_id, $stage_id, $grade_id, $division_id) {
                if ($subject_id) {
                    $q->where('subject_id', $subject_id);
                }
                if ($stage_id) {
                    $q->where('stage_id', $stage_id);
                }
                if ($grade_id) {
                    $q->where('grade_id', $grade_id);
                }
                if ($division_id) {
                    $q->where('division_id', $division_id);
                }
            });
        }

        if($is_featured){
            $query->where('is_featured', 1);
        }
        return $query ;
    }

}
