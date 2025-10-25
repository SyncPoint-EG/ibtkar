<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentApprovalsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        return Payment::with(['student', 'lesson.chapter.course.subject', 'lesson.chapter.course.teacher', 'chapter.course.subject', 'chapter.course.teacher', 'course.subject', 'course.teacher'])
            ->filter($this->filters)
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Student ID',
            'Student Name',
            'Date',
            'Time',
            'Payment Method',
            'Subject Name',
            'Course',
            'Lesson',
            'Academic Level',
            'Teacher UUID',
            'Teacher Name',
            'Status',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->id,
            $payment->student_id,
            $payment->student?->first_name . ' ' . $payment->student?->last_name,
            $payment->created_at->format('Y-m-d'),
            $payment->created_at->format('H:i'),
            $payment->payment_method,
            $this->getSubjectName($payment),
            $payment->course?->name,
            $payment->lesson?->name,
            $payment->student?->stage?->name . ' - ' . $payment->student?->grade?->name . ' - ' . $payment->student?->division?->name,
            $this->getTeacherUuid($payment),
            $this->getTeacherName($payment),
            $payment->payment_status,
        ];
    }

    private function getSubjectName($payment)
    {
        if ($payment->lesson) {
            return $payment->lesson?->chapter?->course?->subject?->name;
        } elseif ($payment->chapter) {
            return $payment->chapter?->course?->subject?->name;
        } elseif ($payment->course) {
            return $payment->course?->subject?->name;
        }
        return '';
    }

    private function getTeacherUuid($payment)
    {
        if ($payment->lesson) {
            return $payment->lesson?->chapter?->course?->teacher?->uuid;
        } elseif ($payment->chapter) {
            return $payment->chapter?->course?->teacher?->uuid;
        } elseif ($payment->course) {
            return $payment->course?->teacher?->uuid;
        }
        return '';
    }

    private function getTeacherName($payment)
    {
        if ($payment->lesson) {
            return $payment->lesson?->chapter?->course?->teacher?->name;
        } elseif ($payment->chapter) {
            return $payment->chapter?->course?->teacher?->name;
        } elseif ($payment->course) {
            return $payment->course?->teacher?->name;
        }
        return '';
    }
}
