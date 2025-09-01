<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Student::with('guardian', 'district', 'center', 'stage', 'grade', 'division')->get();
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Phone',
            'Guardian Phone',
            'District',
            'Center',
            'Stage',
            'Grade',
            'Division',
            'Gender',
            'Birth Date',
            'Status',
            'Referral Code',
            'Points',
            'Purchased Lessons',
        ];
    }

    public function map($student): array
    {
        return [
            $student->first_name,
            $student->last_name,
            $student->phone,
            $student->guardian?->phone,
            $student->district?->name,
            $student->center?->name,
            $student->stage?->name,
            $student->grade?->name,
            $student->division?->name,
            $student->gender,
            $student->birth_date ? $student->birth_date->format('Y-m-d') : '',
            $student->status ? 'Active' : 'Inactive',
            $student->referral_code,
            $student->points,
            $student->purchased_lessons_count,
        ];
    }
}