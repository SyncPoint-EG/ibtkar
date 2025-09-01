<?php

namespace App\Imports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;

class TeachersImport implements ToModel, WithHeadingRow, WithValidation
{
    use RemembersRowNumber;

    private $errors = [];

    public function model(array $row)
    {
        $teacher = Teacher::where('phone', $row['phone'])->orWhere('name', $row['name'])->first();

        if ($teacher) {
            $teacher->update([
                'name' => $row['name'],
                'phone' => $row['phone'],
                'status' => $row['status'] == 'Active' ? 1 : 0,
                'is_featured' => $row['is_featured'] == 'Yes' ? 1 : 0,
            ]);
        } else {
            Teacher::create([
                'name' => $row['name'],
                'phone' => $row['phone'],
                'status' => $row['status'] == 'Active' ? 1 : 0,
                'is_featured' => $row['is_featured'] == 'Yes' ? 1 : 0,
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
