<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentApprovalService
{
    /**
     * Get all pending payments with pagination.
     */
    public function getPendingPaymentsPaginated(array $filters): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 15;
        $query = Payment::with([
            'student.stage',
            'student.grade',
            'student.division',
            'course.subject',
            'course.teacher',
            'lesson.chapter.course.subject',
            'lesson.chapter.course.teacher',
            'chapter.course.subject',
            'chapter.course.teacher',
        ])->filter($filters);

        return $query->latest()->paginate($perPage);
    }

    public function getPaymentStatistics(): array
    {
        $approvedPayments = Payment::where('payment_status', 'approved');

        $studentsPaidCount = (clone $approvedPayments)->distinct('student_id')->count();
        $lessonsPaidCount = (clone $approvedPayments)->whereNotNull('lesson_id')->count();
        $coursesPaidCount = (clone $approvedPayments)->whereNotNull('course_id')->count();
        $chaptersPaidCount = (clone $approvedPayments)->whereNotNull('chapter_id')->count();

        return [
            'students_paid_count' => $studentsPaidCount,
            'lessons_paid_count' => $lessonsPaidCount,
            'courses_paid_count' => $coursesPaidCount,
            'chapters_paid_count' => $chaptersPaidCount,
        ];
    }

    /**
     * Accept a pending payment.
     *
     * @throws \Exception
     */
    public function acceptPayment(int $paymentId): void
    {
        $payment = Payment::findOrFail($paymentId);

        if ($payment->payment_status !== Payment::PAYMENT_STATUS['pending']) {
            throw new \Exception('Payment is not in pending status.');
        }

        $payment->payment_status = Payment::PAYMENT_STATUS['approved'];
        $payment->save();

        // If the payment was for wallet top-up, add amount to student's wallet
        if ($payment->payment_method === 'wallet') {
            $student = Student::find($payment->student_id);
            if ($student) {
                $student->wallet += $payment->amount;
                $student->save();
            }
        }
    }

    /**
     * Reject a pending payment.
     *
     * @throws \Exception
     */
    public function rejectPayment(int $paymentId): void
    {
        $payment = Payment::findOrFail($paymentId);

        if ($payment->payment_status !== Payment::PAYMENT_STATUS['pending']) {
            throw new \Exception('Payment is not in pending status.');
        }

        $payment->payment_status = Payment::PAYMENT_STATUS['rejected'];
        $payment->save();
    }

    public function createGiftPayment(Lesson $lesson, Student $student): void
    {
        // Check if the student already has an approved payment for this lesson
        $existingPayment = Payment::where('student_id', $student->id)
            ->where('lesson_id', $lesson->id)
            ->where('payment_status', Payment::PAYMENT_STATUS['approved'])
            ->exists();

        if ($existingPayment) {
            throw new \Exception('Student already has an approved payment for this lesson.');
        }

        $payment = new Payment();
        $payment->student_id = $student->id;
        $payment->lesson_id = $lesson->id;
        $payment->amount = $lesson->price;
        $payment->payment_method = 'gift';
        $payment->payment_status = Payment::PAYMENT_STATUS['approved'];
        $payment->save();
    }
}
