<?php

namespace App\Exports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TeachersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Teacher::with('courses.grade', 'courses.subject')->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Phone',
            'Grades',
            'Subjects',
            'Status',
            'Is Featured',
        ];
    }

    public function map($teacher): array
    {
        return [
            $teacher->name,
            $teacher->phone,
            $teacher->courses->pluck('grade.name')->unique()->implode(', '),
            $teacher->courses->pluck('subject.name')->unique()->implode(', '),
            $teacher->status ? 'Active' : 'Inactive',
            $teacher->is_featured ? 'Yes' : 'No',
        ];
    }
}
