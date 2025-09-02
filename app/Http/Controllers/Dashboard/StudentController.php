<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Models\Center;
use App\Models\District;
use App\Models\Division;
use App\Models\Governorate;
use App\Models\Grade;
use App\Models\Stage;
use App\Services\StudentService;
use App\Models\Student;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    protected StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $filters = $request->only([
            'name', 'phone', 'governorate_id', 'center_id', 'stage_id', 
            'grade_id', 'division_id', 'education_type_id', 'status', 'gender'
        ]);

        $students = $this->studentService->getAllPaginated(15, $filters);

        $governorates = Governorate::all();
        $centers = Center::all();
        $stages = Stage::all();
        $grades = Grade::all();
        $divisions = Division::all();
        $educationTypes = \App\Models\EducationType::all();

        return view('dashboard.students.index', compact(
            'students', 'filters', 'governorates', 'centers', 'stages', 
            'grades', 'divisions', 'educationTypes'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $governorates = Governorate::all();
        $districts = District::all();
        $centers = Center::all();
        $stages = Stage::all();
        $grades = Grade::all();
        $divisions = Division::all();
        $educationTypes = \App\Models\EducationType::all();
        return view('dashboard.students.create',compact('governorates','districts','centers','stages','grades','divisions','educationTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StudentRequest $request
     * @return RedirectResponse
     */
    public function store(StudentRequest $request): RedirectResponse
    {
        try {
            $this->studentService->create($request->validated());

            return redirect()->route('students.index')
                ->with('success', 'Student created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Student: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Student $student
     * @return View
     */
    public function show(Student $student): View
    {
        return view('dashboard.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Student $student
     * @return View
     */
    public function edit(Student $student): View
    {
        $governorates = Governorate::all();
        $districts = District::all();
        $centers = Center::all();
        $stages = Stage::all();
        $grades = Grade::all();
        $divisions = Division::all();
        $educationTypes = \App\Models\EducationType::all();
        return view('dashboard.students.edit', compact('student','governorates','districts','centers','stages','grades','divisions','educationTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StudentRequest $request
     * @param Student $student
     * @return RedirectResponse
     */
    public function update(StudentRequest $request, Student $student): RedirectResponse
    {
        try {
            $this->studentService->update($student, $request->validated());

            return redirect()->route('students.index')
                ->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Student: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Student $student
     * @return RedirectResponse
     */
    public function destroy(Student $student): RedirectResponse
    {
        try {
            $this->studentService->delete($student);

            return redirect()->route('students.index')
                ->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting Student: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $filters = $request->only([
            'name', 'phone', 'governorate_id', 'center_id', 'stage_id', 
            'grade_id', 'division_id', 'education_type_id', 'status', 'gender'
        ]);

        return Excel::download(new StudentsExport($filters), 'students.xlsx');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $import = new StudentsImport();
        try {
            Excel::import($import, $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return redirect()->back()->with('error', $errors);
        }

        if (!empty($import->getErrors())) {
            return redirect()->back()->with('error', $import->getErrors());
        }

        return redirect()->route('students.index')
            ->with('success', 'Students imported successfully.');
    }
}
