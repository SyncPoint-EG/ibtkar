<?php

namespace App\Exports;

use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Watch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LessonStudentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $lesson;

    public function __construct(Lesson $lesson)
    {
        $this->lesson = $lesson;
    }

    public function collection()
    {
        return Payment::where('lesson_id', $this->lesson->id)
            ->where('payment_status', Payment::PAYMENT_STATUS['approved'])
            ->with('student') // Eager load student details
            ->get();
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'Student Name',
            'Student Phone',
            'Payment ID',
            'Payment Status',
            'Payment Method',
            'Amount',
            'Payment Code',
            'Is Watched',
            'Watch Count',
            'Date',
        ];
    }

    public function map($payment): array
    {
        $watch = Watch::where('student_id', $payment?->student->id)->where('lesson_id', $payment?->lesson->id)->first();

        return [
            $payment->student->id,
            $payment->student->name,
            $payment->student->phone,
            $payment->id,
            $payment->payment_status,
            $payment->payment_method,
            $payment->amount,
            $payment->payment_code,
            $watch ? 'Yes' : 'No',
            $payment?->student?->watches_count,
            $payment->created_at->format('d-m-Y H:i'),
        ];
    }
}
