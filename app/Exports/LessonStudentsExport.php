<?php

namespace App\Exports;

use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Watch;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LessonStudentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $lesson;
    protected $payments;
    protected $watches;

    public function __construct(Lesson $lesson)
    {
        $this->lesson = $lesson;
        $this->payments = Payment::where('lesson_id', $this->lesson->id)
            ->where('payment_status', Payment::PAYMENT_STATUS['approved'])
            ->with(['student.guardian', 'lesson'])
            ->get();

        $studentIds = $this->payments->pluck('student_id')->filter();
        $this->watches = Watch::where('lesson_id', $this->lesson->id)
            ->whereIn('student_id', $studentIds)
            ->get()
            ->keyBy('student_id');
    }

    public function collection()
    {
        return $this->payments;
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'Student Name',
            'Student Phone',
            'Guardian Phone',
            'Lesson ID',
            'Lesson Name',
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
        $watch = $this->watches->get($payment?->student?->id);

        return [
            $payment?->student?->id,
            $payment?->student?->name,
            $payment?->student?->phone,
            $payment?->student?->guardian?->phone,
            $payment?->lesson?->id,
            $payment?->lesson?->name,
            $payment->id,
            $payment->payment_status,
            $payment->payment_method,
            $payment->amount,
            $payment->payment_code,
            $watch ? 'Yes' : 'No',
            $watch?->count,
            $payment?->created_at?->format('d-m-Y H:i'),
        ];
    }
}