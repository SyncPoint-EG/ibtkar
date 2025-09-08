<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonRequest;
use App\Http\Requests\TeacherRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\LessonResource;
use App\Http\Resources\SingleTeacherResource;
use App\Http\Resources\TeacherResource;
use App\Models\Division;
use App\Models\Grade;
use App\Models\Lesson;
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
        $student = auth('student')->user();
        // Build query with eager loading of assignments and related models
        $query = Teacher::with([
            'courses.subject',
            'courses.grade',
            'courses.stage',
            'courses.division'
        ])->whereHas('subjectTeacherAssignments',function ($query) use($student) {
            $query->where('stage_id',$student->stage_id)
                ->where('grade_id',$student->grade_id)
                ->where('division_id',$student->division_id);
        });

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
        $student = auth('student')->user();
        $courses = $teacher->courses()->with(['chapters.lessons', 'stage', 'grade'])
            ->where('stage_id',$student->stage->id)
            ->where('grade_Id',$student->grade_id)
            ->where('division_id',$student->division_id)->get();

        $timeline = $courses->groupBy(function ($course) {
            return $course->stage->name . ' - ' . $course->grade->name;
        });

        return response()->json($timeline);
    }

    public function lessonsBySubject( $teacherID)
    {
        $student = auth('student')->user();
        $lessons = Lesson::whereHas('chapter.course', function ($q) use ($teacherID, $student) {
            $q->where('teacher_id', $teacherID)
                ->where('stage_id',$student->stage->id)
                ->where('grade_Id',$student->grade_id)
                ->where('division_id',$student->division_id);

        })
            ->whereDate('date', '>=', now())
          ->with(['chapter','attachments','homework','exams'])
          ->get();


        $lessonsBySubject = $lessons->groupBy(function ($lesson) {
            return $lesson->chapter->course->subject->name;
        });

        return LessonResource::collection($lessons);
    }

    public function filter($query , $request)
    {
        // Get filter parameters
        $search        = $request->get('search');
        $status        = $request->get('status');
        $rate          = $request->get('rate');
        $stage_id      = $request->get('stage_id');
        $subject_id    = $request->get('subject_id');
        $education_type_id    = $request->get('education_type_id') ; // Optional
        $grade_id      = $request->get('grade_id'); // Optional
        $division_id   = $request->get('division_id'); // Optional
        $is_featured   = $request->get('is_featured'); // to display in home screen
        $lesson_price   = $request->get('lesson_price'); // to display in home screen


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
        if ($subject_id || $stage_id || $grade_id || $division_id || $education_type_id) {
            $query->whereHas('courses', function ($q) use ($subject_id, $stage_id, $grade_id, $division_id, $education_type_id)  {
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
                if ($education_type_id) {
                    $q->where('education_type_id', $education_type_id);
                }
            });
        }

        if($is_featured){
            $query->where('is_featured', 1);
        }
        if($lesson_price){
            $query->whereHas('courses.chapters.lessons', function ($q) use ($lesson_price) {
                    $q->where('price','<=', $lesson_price);
            });
        }
        return $query ;
    }

}
