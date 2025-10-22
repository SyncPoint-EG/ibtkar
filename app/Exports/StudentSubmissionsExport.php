<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentSubmissionsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function query()
    {
        return $this->student->examAttempts()->with('exam.lesson');
    }

    public function headings(): array
    {
        return [
            __('dashboard.exam.title'),
            __('dashboard.lesson.title'),
            __('dashboard.exam.fields.score'),
            __('dashboard.exam.fields.total_score'),
            __('dashboard.common.date'),
        ];
    }

    public function map($attempt): array
    {
        return [
            $attempt->exam->name,
            $attempt->exam->lesson?->name,
            $attempt->score,
            $attempt->exam->total_score,
            $attempt->created_at->format('Y-m-d H:i A'),
        ];
    }
}
