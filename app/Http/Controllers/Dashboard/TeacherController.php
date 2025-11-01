<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\TeachersExport;
use App\Exports\TeacherStudentsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRequest;
use App\Imports\TeachersImport;
use App\Models\Course;
use App\Models\Division;
use App\Models\Grade;
use App\Models\Payment;
use App\Models\Stage;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Watch;
use App\Services\TeacherService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{
    protected TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Get filter parameters
        $search = $request->get('search');
        $status = $request->get('status');
        $stage_id = $request->get('stage_id');
        $subject_id = $request->get('subject_id');
        $grade_id = $request->get('grade_id'); // Optional
        $division_id = $request->get('division_id'); // Optional
        $sort_by = $request->get('sort_by', 'id');
        $sort_direction = $request->get('sort_direction', 'asc');
        $per_page = $request->get('per_page', 10);

        // Build query with eager loading of assignments and related models
        $query = Teacher::with([
            'courses.subject',
            'courses.grade',
            'courses.stage',
            'courses.division',
        ]);

        //            ->withCount(['students', 'lectures', 'courses']);

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('other_phone', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        // Filter by subject_teacher pivot assignments
        if ($subject_id || $stage_id || $grade_id || $division_id) {
            $query->whereHas('subjectTeacherAssignments', function ($q) use ($subject_id, $stage_id, $grade_id, $division_id) {
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

        // Sorting
        $query->orderBy($sort_by, $sort_direction);

        // Paginate results
        $teachers = $query->paginate($per_page)->withQueryString();

        // Get filter option lists
        $subjects = Subject::all();
        $stages = Stage::all();
        $grades = Grade::all();
        $divisions = Division::all();

        // Statistics (adjust as needed)
        $stats = [
            'total_teachers' => Teacher::count(),
            'active_teachers' => Teacher::where('status', 1)->count(),
            'inactive_teachers' => Teacher::where('status', 0)->count(),
            'average_students_per_teacher' => Student::count(), // Adjust as needed
        ];

        return view('dashboard.teachers.index', compact(
            'teachers',
            'subjects',
            'stages',
            'grades',
            'divisions',
            'stats',
            'search',
            'status',
            'stage_id',
            'subject_id',
            'grade_id',
            'division_id',
            'sort_by',
            'sort_direction',
            'per_page'
        ));
    }

    /**
     * Toggle teacher activation status
     */
    public function toggleActivation(Teacher $teacher): JsonResponse
    {
        try {
            $teacher->status = ! $teacher->status;
            $teacher->save();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.teacher.status_updated'),
                'status' => $teacher->status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('dashboard.common.error').': '.$e->getMessage(),
            ], 500);
        }
    }

    public function toggleFeatured(Teacher $teacher): JsonResponse
    {
        try {
            $teacher->is_featured = ! $teacher->is_featured;
            $teacher->save();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.teacher.featured_status_updated'),
                'is_featured' => $teacher->is_featured,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('dashboard.common.error').': '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export teachers data
     */
    public function export()
    {
        return Excel::download(new TeachersExport, 'teachers.xlsx');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $import = new TeachersImport;
        try {
            Excel::import($import, $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Row '.$failure->row().': '.implode(', ', $failure->errors());
            }

            return redirect()->back()->with('error', $errors);
        }

        if (! empty($import->getErrors())) {
            return redirect()->back()->with('error', $import->getErrors());
        }

        return redirect()->route('teachers.index')
            ->with('success', 'Teachers imported successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $subjects = Subject::all();
        $stages = Stage::all();
        $grades = Grade::all();
        $divisions = Division::all();

        return view('dashboard.teachers.create', compact('subjects', 'stages', 'grades', 'divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeacherRequest $request): RedirectResponse
    {
        //        try {
        $data = $request->validated();
        $data['is_featured'] = $request->has('is_featured');

        $teacher = $this->teacherService->create($data);

        //            // Handle subject assignments
        $assignments = $request->input('assignments', []);
        $syncData = [];
        foreach ($assignments as $assignment) {
            if (! empty($assignment['subject_id']) && ! empty($assignment['stage_id']) && ! empty($assignment['grade_id'])) {
                $syncData[$assignment['subject_id']] = [
                    'stage_id' => $assignment['stage_id'],
                    'grade_id' => $assignment['grade_id'],
                    'division_id' => $assignment['division_id'] ?? null,
                    'day_of_week' => $assignment['day_of_week'] ?? null,
                    'time' => $assignment['time'] ?? null,
                ];
            }
        }
        if (! empty($syncData)) {
            $teacher->subjects()->attach($syncData);
        }

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher created successfully.');
        //        } catch (\Exception $e) {
        //            return redirect()->back()
        //                ->withInput()
        //                ->with('error', 'Error creating Teacher: ' . $e->getMessage());
        //        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher): View
    {
        $teacher->load(['subjects', 'grades', 'stages', 'divisions']);

        return view('dashboard.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher): View
    {
        $teacher->load('subjects'); // Eager-load assignments
        $subjects = Subject::all();
        $stages = Stage::all();
        $grades = Grade::all();
        $divisions = Division::all();

        return view('dashboard.teachers.edit', compact('teacher', 'subjects', 'stages', 'grades', 'divisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        try {
            $data = $request->except('password');
            $data['is_featured'] = $request->has('is_featured');

            $this->teacherService->update($teacher, $data);

            if ($request->password != null) {
                $teacher->password = $request->password;
                $teacher->save();
            }
            //            // Handle subject assignments
            $assignments = $request->input('assignments', []);
            $teacher->subjects()->detach();
            foreach ($assignments as $assignment) {
                if (! empty($assignment['subject_id']) && ! empty($assignment['stage_id']) && ! empty($assignment['grade_id'])) {
                    $teacher->subjects()->attach($assignment['subject_id'], [
                        'stage_id' => $assignment['stage_id'],
                        'grade_id' => $assignment['grade_id'],
                        'division_id' => $assignment['division_id'] ?? null,
                        'day_of_week' => $assignment['day_of_week'] ?? null,
                        'time' => $assignment['time'] ?? null,
                    ]);
                }
            }

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Teacher: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher): RedirectResponse
    {
        //        try {
        $this->teacherService->delete($teacher);

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher deleted successfully.');
        //        } catch (\Exception $e) {
        //            return redirect()->back()
        //                ->with('error', 'Error deleting Teacher: ' . $e->getMessage());
        //        }
    }

    /**
     * AJAX search for teachers
     */
    public function search(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $stage_id = $request->get('stage_id');
        $subject_id = $request->get('subject_id');
        $grade_id = $request->get('grade_id');       // Add this if filtering by grade
        $division_id = $request->get('division_id');    // Add this for division filter
        $sort_by = $request->get('sort_by', 'id');
        $sort_direction = $request->get('sort_direction', 'asc');
        $per_page = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        // Build query with eager loading of assignments and related models
        $query = Teacher::with([
            'courses.subject',
            'courses.grade',
            'courses.stage',
            'courses.division',
        ]);
        //            ->withCount(['students', 'courses', 'lectures']);

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('other_phone', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        // Filter by assignments pivot data
        if ($subject_id || $stage_id || $grade_id || $division_id) {
            //            $query->whereHas('courses', function($q) use ($subject_id, $stage_id, $grade_id, $division_id) {
            $query->whereHas('subjectTeacherAssignments', function ($q) use ($subject_id, $stage_id, $grade_id, $division_id) {
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

        // Sorting
        $query->orderBy($sort_by, $sort_direction);

        // Paginate results
        $teachers = $query->paginate($per_page, ['*'], 'page', $page);

        // Return JSON response with rendered partials
        return response()->json([
            'success' => true,
            'html' => view('dashboard.teachers.partials.table', compact('teachers'))->render(),
            'pagination' => view('dashboard.teachers.partials.pagination', compact('teachers'))->render(),
            'total' => $teachers->total(),
            'current_page' => $teachers->currentPage(),
            'last_page' => $teachers->lastPage(),
        ]);
    }

    public function getCourses(Teacher $teacher)
    {
        return response()->json($teacher->courses->pluck('name', 'id'));
    }

    public function students(Request $request, Teacher $teacher)
    {
        $courseIds = $teacher->courses()->pluck('id');

        $chapterIds = \App\Models\Chapter::whereIn('course_id', $courseIds)->pluck('id');

        $lessonIds = \App\Models\Lesson::whereIn('chapter_id', $chapterIds)->pluck('id');

        $studentIds = \App\Models\Payment::where('payment_status', Payment::PAYMENT_STATUS['approved'])
            ->where(function ($query) use ($courseIds, $chapterIds, $lessonIds) {
                $query->whereIn('course_id', $courseIds)
                    ->orWhereIn('chapter_id', $chapterIds)
                    ->orWhereIn('lesson_id', $lessonIds);
            })
            ->pluck('student_id')->unique();

        $studentsFromWatches = Watch::whereIn('lesson_id',$lessonIds)->pluck('student_id')->unique();

        $allUniqueStudentIds = $studentIds->merge($studentsFromWatches)->unique();
        $studentsQuery = Student::whereIn('id', $allUniqueStudentIds);

        // Filtering
        if ($request->filled('search')) {
            $studentsQuery->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->input('search').'%')
                    ->orWhere('phone', 'like', '%'.$request->input('search').'%');
            });
        }

        if ($request->filled('stage_id')) {
            $studentsQuery->whereHas('grade.stage', function ($q) use ($request) {
                $q->where('id', $request->input('stage_id'));
            });
        }

        if ($request->filled('grade_id')) {
            $studentsQuery->where('grade_id', $request->input('grade_id'));
        }

        if ($request->filled('division_id')) {
            $studentsQuery->where('division_id', $request->input('division_id'));
        }

        $students = $studentsQuery->paginate(10)->withQueryString();

        // Get filter options
        $assignments = $teacher->subjectTeacherAssignments()->with('stage', 'grade', 'division')->get();
        $stages = $assignments->pluck('stage')->unique()->whereNotNull();
        $grades = $assignments->pluck('grade')->unique()->whereNotNull();
        $divisions = $assignments->pluck('division')->unique()->whereNotNull();

        return view('dashboard.teachers.students', compact('teacher', 'students', 'stages', 'grades', 'divisions'));
    }

    public function exportStudents(Request $request, Teacher $teacher)
    {
        return Excel::download(new TeacherStudentsExport($teacher, $request->all()), 'students.xlsx');
    }

    public function generateReport(Request $request, Teacher $teacher)
    {
        $startDateInput = $request->get('start_date');
        $endDateInput = $request->get('end_date');
        $startDate = $startDateInput ? Carbon::parse($startDateInput)->startOfDay() : null;
        $endDate = $endDateInput ? Carbon::parse($endDateInput)->endOfDay() : null;

        $paymentsQuery = Payment::query()
            ->where('payment_status', Payment::PAYMENT_STATUS['approved'])
            ->where(function ($query) use ($teacher) {
                $query->whereHas('course', function ($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id);
                })->orWhereHas('chapter.course', function ($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id);
                })->orWhereHas('lesson.chapter.course', function ($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id);
                });
            });

        if ($startDate && $endDate) {
            $paymentsQuery->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $paymentsQuery->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $paymentsQuery->where('created_at', '<=', $endDate);
        }

        $sumPayments = static function ($query) {
            $aggregate = (clone $query)
                ->selectRaw(
                    'COALESCE(SUM(CASE WHEN total_amount IS NOT NULL THEN total_amount WHEN amount IS NOT NULL THEN amount ELSE 0 END), 0) as aggregate'
                )
                ->value('aggregate');
            return (float) $aggregate;
        };

        $lessonPayments = (clone $paymentsQuery)->whereNotNull('lesson_id');
        $chapterPayments = (clone $paymentsQuery)
            ->whereNull('lesson_id')
            ->whereNotNull('chapter_id');
        $coursePayments = (clone $paymentsQuery)
            ->whereNull('lesson_id')
            ->whereNull('chapter_id')
            ->whereNotNull('course_id');

        $overallSummary = [
            'lessons' => [
                'label' => 'الدروس',
                'count' => (clone $lessonPayments)->count(),
                'total' => $sumPayments($lessonPayments),
            ],
            'chapters' => [
                'label' => 'الفصول',
                'count' => (clone $chapterPayments)->count(),
                'total' => $sumPayments($chapterPayments),
            ],
            'courses' => [
                'label' => 'الكورسات',
                'count' => (clone $coursePayments)->count(),
                'total' => $sumPayments($coursePayments),
            ],
        ];

        $overallTotal = collect($overallSummary)->sum('total');

        $gradeIds = Course::where('teacher_id', $teacher->id)
            ->whereNotNull('grade_id')
            ->pluck('grade_id')
            ->unique()
            ->values();

        $grades = Grade::whereIn('id', $gradeIds)->get();

        $gradeSummaries = $grades->map(function ($grade) use ($paymentsQuery, $sumPayments) {
            $gradePayments = (clone $paymentsQuery)->where(function ($query) use ($grade) {
                $query->whereHas('course', function ($q) use ($grade) {
                    $q->where('grade_id', $grade->id);
                })->orWhereHas('chapter.course', function ($q) use ($grade) {
                    $q->where('grade_id', $grade->id);
                })->orWhereHas('lesson.chapter.course', function ($q) use ($grade) {
                    $q->where('grade_id', $grade->id);
                });
            });

            $gradeLessonPayments = (clone $gradePayments)->whereNotNull('lesson_id');
            $gradeChapterPayments = (clone $gradePayments)
                ->whereNull('lesson_id')
                ->whereNotNull('chapter_id');
            $gradeCoursePayments = (clone $gradePayments)
                ->whereNull('lesson_id')
                ->whereNull('chapter_id')
                ->whereNotNull('course_id');

            $summary = [
                'lessons' => [
                    'label' => 'الدروس',
                    'count' => (clone $gradeLessonPayments)->count(),
                    'total' => $sumPayments($gradeLessonPayments),
                ],
                'chapters' => [
                    'label' => 'الفصول',
                    'count' => (clone $gradeChapterPayments)->count(),
                    'total' => $sumPayments($gradeChapterPayments),
                ],
                'courses' => [
                    'label' => 'الكورسات',
                    'count' => (clone $gradeCoursePayments)->count(),
                    'total' => $sumPayments($gradeCoursePayments),
                ],
            ];

            return [
                'grade' => $grade,
                'summary' => $summary,
                'total' => collect($summary)->sum('total'),
            ];
        });

        $logoPath = public_path('dashboard/app-assets/images/logo.png');
        $logo = null;

        if (file_exists($logoPath)) {
            $logo = 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        $data = [
            'teacher' => $teacher,
            'startDate' => $startDateInput,
            'endDate' => $endDateInput,
            'overallSummary' => $overallSummary,
            'overallTotal' => $overallTotal,
            'gradeSummaries' => $gradeSummaries,
            'logo' => $logo,
        ];

        $pdf = Pdf::loadView('dashboard.teachers.report', $data);

        return $pdf->download('report.pdf');
    }
}
