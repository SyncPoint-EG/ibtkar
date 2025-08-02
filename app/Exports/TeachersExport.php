<?php

namespace App\Exports;

use App\Models\Teacher;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeachersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Eager load necessary relationships
        return Teacher::with([
            'courses.grade',
            'courses.subject',
            'courses.grade',
            'courses.subject',
        ])
            ->get()
            ->map(function ($teacher) {
                // Get unique grades (from courses or assignments)
                $grades = $teacher->courses->pluck('grade')->unique('id')->filter();
                if ($grades->isEmpty()) {
                    $grades = $teacher->courses->pluck('grade')->unique('id')->filter();
                }
                $gradeNames = $grades->pluck('name')->implode(', ');

                // Get unique subjects
                $subjects = $teacher->courses->pluck('subject')->unique('id')->filter();
                if ($subjects->isEmpty()) {
                    $subjects = $teacher->courses->pluck('subject')->unique('id')->filter();
                }
                $subjectNames = $subjects->pluck('name')->implode(', ');

                return [
                    'ID'             => $teacher->id,
                    'Name'           => $teacher->name,
                    'Grades'         => $gradeNames,
                    'Subjects'       => $subjectNames,
                    'Total Students' => $teacher->students_count ?? 0,
                    'Total Lessons'  => $teacher->lessons_count ?? 0,
                    'Status'         => $teacher->status ? 'Active' : 'Inactive',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Grades',
            'Subjects',
            'Total Students',
            'Total Lessons',
            'Status',
        ];
    }
}
