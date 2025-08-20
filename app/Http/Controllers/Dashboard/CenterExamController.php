<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\CenterExam;
use App\Models\Division;
use App\Models\Grade;
use App\Models\Stage;
use App\Models\Teacher;
use App\Services\CenterExamService;
use Illuminate\Http\Request;

class CenterExamController extends Controller
{
    protected $centerExamService;

    public function __construct(CenterExamService $centerExamService)
    {
        $this->centerExamService = $centerExamService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $centerExams = CenterExam::with(['center', 'stage', 'grade', 'division'])->paginate(10);
        return view('dashboard.center_exams.index', compact('centerExams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $centers = Center::all();
        $stages = Stage::all();
        $grades = Grade::all();
        $divisions = Division::all(); // Or filter based on stage/grade if needed
        $teachers = Teacher::all();
        return view('dashboard.center_exams.create', compact('centers', 'stages', 'grades', 'divisions', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'center_id' => 'required|exists:centers,id',
            'teacher_id' => 'required|exists:teachers,id',
            'stage_id' => 'required|exists:stages,id',
            'grade_id' => 'required|exists:grades,id',
            'division_id' => 'nullable|exists:divisions,id',
            'description' => 'nullable|string',
            'total_marks' => 'required|integer',
            'passing_marks' => 'required|integer',
            'duration_minutes' => 'required|integer',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'is_active' => 'boolean',
        ]);

        $this->centerExamService->createCenterExam($data);

        return redirect()->route('center-exams.index')
            ->with('success', 'Center Exam created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CenterExam $centerExam)
    {
        $centerExam->load(['center', 'stage', 'grade', 'division', 'questions.options']);
        return view('dashboard.center_exams.show', compact('centerExam'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CenterExam $centerExam)
    {
        $centers = Center::all();
        $stages = Stage::all();
        $grades = Grade::all();
        $divisions = Division::all(); // Or filter based on stage/grade if needed
        $teachers = Teacher::all();
        return view('dashboard.center_exams.edit', compact('centerExam', 'centers', 'stages', 'grades', 'divisions', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CenterExam $centerExam)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'center_id' => 'required|exists:centers,id',
            'teacher_id' => 'required|exists:teachers,id',
            'stage_id' => 'required|exists:stages,id',
            'grade_id' => 'required|exists:grades,id',
            'division_id' => 'nullable|exists:divisions,id',
            'description' => 'nullable|string',
            'total_marks' => 'required|integer',
            'passing_marks' => 'required|integer',
            'duration_minutes' => 'required|integer',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'is_active' => 'boolean',
        ]);

        $this->centerExamService->updateCenterExam($centerExam->id, $data);

        return redirect()->route('center-exams.index')
            ->with('success', 'Center Exam updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CenterExam $centerExam)
    {
        $this->centerExamService->deleteCenterExam($centerExam->id);
        return redirect()->route('center-exams.index')
            ->with('success', 'Center Exam deleted successfully.');
    }

    public function submissions(CenterExam $centerExam)
    {
        $centerExam->load('attempts.student', 'attempts.answers.question.options');
        return view('dashboard.center_exams.submissions', compact('centerExam'));
    }
}

