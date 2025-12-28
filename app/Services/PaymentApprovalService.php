<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Student;
use App\Traits\FirebaseNotify;
use App\Services\WhatsappNotificationService;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentApprovalService
{
    use FirebaseNotify;

    public function __construct(private readonly WhatsappNotificationService $whatsappNotificationService)
    {
    }

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
            'gradePlan',
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

        $this->notifyPaymentApproval($payment);

        if ($payment->lesson_id) {
            $this->whatsappNotificationService->sendLessonPurchaseNotification($payment);
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

    protected function notifyPaymentApproval(Payment $payment): void
    {
        if (! in_array($payment->payment_method, ['wallet', 'instapay'], true)) {
            return;
        }

        $payment->loadMissing(
            'student.guardian',
            'lesson.chapter.course.teacher',
            'chapter.course.teacher',
            'course.teacher'
        );

        $student = $payment->student;
        if (! $student) {
            return;
        }

        $resource = $this->resolvePaymentResource($payment);
        if (! $resource) {
            return;
        }

        [$resourceType, $resourceId, $resourceName] = $resource;
        $resourceLabel = $this->formatResourceLabel($resourceType, $resourceName);

        $data = [
            'type' => 'payment_approved',
            'payment_id' => (string) $payment->id,
            'resource_type' => $resourceType,
            'resource_id' => (string) $resourceId,
            'student_id' => (string) $student->id,
        ];

        $studentTitle = 'تم تفعيل المحتوى الدراسي';
        $studentBody = sprintf('%s متاحة الآن. نتمنى لك تجربة موفقة!', $resourceLabel);
        $this->sendAndStoreFirebaseNotification($student, $studentTitle, $studentBody, $data);

        $guardian = $student->guardian;
        if (! $guardian) {
            return;
        }

        $guardianTitle = 'تمت الموافقة على شراء '.$student->name;
        $guardianBody = sprintf('تمت الموافقة على شراء %s للطالب %s. المحتوى متاح الآن.', $resourceLabel, $student->name);
        $this->sendAndStoreFirebaseNotification($guardian, $guardianTitle, $guardianBody, $data);
    }

    protected function resolvePaymentResource(Payment $payment): ?array
    {
        if ($payment->lesson) {
            return ['lesson', $payment->lesson->id, $payment->lesson->name];
        }

        if ($payment->chapter) {
            return ['chapter', $payment->chapter->id, $payment->chapter->name];
        }

        if ($payment->course) {
            return ['course', $payment->course->id, $payment->course->name];
        }

        return null;
    }

    protected function formatResourceLabel(string $resourceType, string $resourceName): string
    {
        return match ($resourceType) {
            'lesson' => 'الحصة "'.$resourceName.'"',
            'chapter' => 'الشابتر "'.$resourceName.'"',
            'course' => 'الكورس "'.$resourceName.'"',
            default => $resourceName,
        };
    }
}
