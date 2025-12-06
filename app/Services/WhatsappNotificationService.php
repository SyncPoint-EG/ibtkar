<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Payment;
use App\Models\Student;
use App\Traits\WhatsappTrait;
use Illuminate\Support\Facades\Log;

class WhatsappNotificationService
{
    use WhatsappTrait;

    public function sendLessonPurchaseNotification(Payment $payment): void
    {
        if (! $payment->lesson_id) {
            return;
        }

        $payment->loadMissing('lesson.chapter.course.subject', 'student.guardian');

        $student = $payment->student;
        $lesson = $payment->lesson;
        $guardian = $student?->guardian;

        if (! $student || ! $lesson || ! $guardian || ! $guardian->phone) {
            return;
        }

        $subjectName = $lesson->chapter?->course?->subject?->name ?? 'المادة';

        $message = $this->buildLessonPurchaseMessage(
            $student->name,
            $lesson->name,
            $subjectName
        );

        $this->sendSilently($message, $guardian->phone, [
            'payment_id' => $payment->id,
            'lesson_id' => $lesson->id,
            'student_id' => $student->id,
        ]);
    }

    public function sendExamPassNotification(Student $student, Exam $exam, ExamAttempt $examAttempt): void
    {
        $student->loadMissing('guardian');
        $guardian = $student->guardian;

        if (! $guardian || ! $guardian->phone || ! $examAttempt->is_passed) {
            return;
        }

        $course = $exam->course ?? $exam->lesson?->chapter?->course;
        $subjectName = $course?->subject?->name ?? 'المادة';

        $message = $this->buildExamPassMessage(
            $student->name,
            $exam->title,
            $subjectName,
            $examAttempt->score
        );

        $this->sendSilently($message, $guardian->phone, [
            'exam_attempt_id' => $examAttempt->id,
            'exam_id' => $exam->id,
            'student_id' => $student->id,
        ]);
    }

    protected function sendSilently(string $message, string $phone, array $context = []): void
    {
        try {
            $response = self::sendMsg($message, $phone);

            if ($response === null) {
                Log::warning('UltraMsg WhatsApp send did not return success.', array_merge($context, [
                    'phone' => $phone,
                ]));
            }
        } catch (\Throwable $exception) {
            Log::error('UltraMsg WhatsApp send encountered an exception.', array_merge($context, [
                'phone' => $phone,
                'message' => $exception->getMessage(),
            ]));
        }
    }

    private function buildLessonPurchaseMessage(string $studentName, string $lessonName, string $subjectName): string
    {
        return <<<EOT
ولي أمر الطالب {$studentName}
حابين نطمن حضرتك ان ابنك/بنتك اشترك بنجاح في محاضرة ال {$lessonName} لمادة {$subjectName} على منصة *ابتكار💡*
تقدر حضرتك برده تتابع دخولة المحاضرات  من الاكونت الخاص ب ولي الامر بعد ما تحمل الابلكيشن حضرتك بتعمل تسجيل دخول برقم حضرتك و كلمة المرور هتكون نفس رقم حضرتك برده💖
{$this->footerText()}
EOT;
    }

    private function buildExamPassMessage(string $studentName, string $examTitle, string $subjectName, $score): string
    {
        return <<<EOT
ولي أمر الطالب {$studentName}
حابين نبلغ حضرتك ان ابنك/بنتك قد اجتاز بنجاح امتحان {$examTitle} لمادة {$subjectName} و درجته/ها {$score} على منصة  *ابتكار💡*

تقدر حضرتك برده تتابع مستواه الدراسي و اخطاءه داخل الامتحان لتحسين اداءه الداسي الاكونت الخاص ب ولي الامر بعد ما تحمل الابلكيشن حضرتك بتعمل تسجيل دخول برقم حضرتك و كلمة المرور هتكون نفس رقم حضرتك برده💖
{$this->footerText()}
EOT;
    }

    private function footerText(): string
    {
        return <<<EOT
لينك تحميل الابلكيشن للاندرويد ⬇️
https://play.google.com/store/apps/details?id=com.syncpoint.ibtikar

احنا في ابتكار بنسعى ديما نكون معاكم خطوة ب خطوة لاي استفسار او مساعدة الرجاء ارسال رسالة الي الدعم الفني على الرقم ده⬇️
01030906529

لان النجاح عمره ما كان صدفة النجاح ابتكار..🤩💪🏻
EOT;
    }
}
