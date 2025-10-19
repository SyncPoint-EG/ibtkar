<?php

namespace App\Exports;

use App\Models\Exam;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SubmissionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $exam;

    public function __construct(Exam $exam)
    {
        $this->exam = $exam;
    }

    public function collection()
    {
        return $this->exam->attempts()->with('student')->get();
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Student Phone',
            'Guardian Phone',
            'Score',
            'Total Marks',
            'Submitted At',
        ];
    }

    public function map($attempt): array
    {
        return [
            $attempt->student->name,
            $attempt->student->phone,
            $attempt?->student?->guardian?->phone,
            $attempt->score,
            $attempt->total_marks,
            $attempt->created_at->format('d-m-Y H:i'),
        ];
    }
}
