<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Guardian;
use App\Models\District;
use App\Models\Center;
use App\Models\Stage;
use App\Models\Grade;
use App\Models\Division;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    use RemembersRowNumber;

    private $errors = [];

    public function model(array $row)
    {
        $guardian = Guardian::where('phone', $row['guardian_phone'])->first();
        $district = District::where('name', $row['district'])->first();
        $center = Center::where('name', $row['center'])->first();
        $stage = Stage::where('name', $row['stage'])->first();
        $grade = Grade::where('name', $row['grade'])->first();
        $division = Division::where('name', $row['division'])->first();

        if (!$guardian) {
            $this->errors[] = 'Guardian with phone ' . $row['guardian_phone'] . ' not found at row ' . $this->getRowNumber();
            return null;
        }

        if (!$district) {
            $this->errors[] = 'District with name ' . $row['district'] . ' not found at row ' . $this->getRowNumber();
            return null;
        }

        if (!$center) {
            $this->errors[] = 'Center with name ' . $row['center'] . ' not found at row ' . $this->getRowNumber();
            return null;
        }

        if (!$stage) {
            $this->errors[] = 'Stage with name ' . $row['stage'] . ' not found at row ' . $this->getRowNumber();
            return null;
        }

        if (!$grade) {
            $this->errors[] = 'Grade with name ' . $row['grade'] . ' not found at row ' . $this->getRowNumber();
            return null;
        }

        if (!$division) {
            $this->errors[] = 'Division with name ' . $row['division'] . ' not found at row ' . $this->getRowNumber();
            return null;
        }

        $student = Student::where('phone', $row['phone'])->orWhere(function ($query) use ($row) {
            $query->where('first_name', $row['first_name'])
                  ->where('last_name', $row['last_name']);
        })->first();

        if ($student) {
            $student->update([
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'phone' => $row['phone'],
                'guardian_id' => $guardian->id,
                'district_id' => $district->id,
                'center_id' => $center->id,
                'stage_id' => $stage->id,
                'grade_id' => $grade->id,
                'division_id' => $division->id,
                'gender' => $row['gender'],
                'birth_date' => $row['birth_date'],
                'status' => $row['status'] == 'Active' ? 1 : 0,
            ]);
        } else {
            Student::create([
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'phone' => $row['phone'],
                'guardian_id' => $guardian->id,
                'district_id' => $district->id,
                'center_id' => $center->id,
                'stage_id' => $stage->id,
                'grade_id' => $grade->id,
                'division_id' => $division->id,
                'gender' => $row['gender'],
                'birth_date' => $row['birth_date'],
                'status' => $row['status'] == 'Active' ? 1 : 0,
            ]);
        }

        return null;
    }

    public function rules(): array
    {
        return [];
    }

    public function getErrors()
    {
        return $this->errors;
    }
}