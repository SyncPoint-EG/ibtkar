<?php

namespace App\Exports;

use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchasedLessonsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function collection()
    {
        return Lesson::whereHas('payments', function ($query) {
            $query->where('student_id', $this->student->id)->where('payment_status', Payment::PAYMENT_STATUS['approved']);
        })->orWhereHas('chapter.payments',function ($q){
            $q->where('student_id', $this->student->id)->where('payment_status', Payment::PAYMENT_STATUS['approved']);
        })->orWhereHas('chapter.course.payments', function ($query) {
            $query->where('student_id', $this->student->id)->where('payment_status', Payment::PAYMENT_STATUS['approved']);
        })->get();
    }

    public function headings(): array
    {
        return [
            'Lesson Name',
            'Teacher',
            'Course',
            'Price',
        ];
    }

    public function map($lesson): array
    {
        return [
            $lesson->name,
            $lesson->chapter->course->teacher->name ?? '',
            $lesson->chapter->course->name ?? '',
            $lesson->price,
        ];
    }
}
