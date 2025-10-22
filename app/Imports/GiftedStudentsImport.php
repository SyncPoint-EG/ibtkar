<?php

namespace App\Imports;

use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class GiftedStudentsImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    protected Lesson $lesson;
    public int $importedCount = 0;
    public array $notFoundPhones = [];

    public function __construct(Lesson $lesson)
    {
        $this->lesson = $lesson;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (empty($row['phone'])) {
                continue;
            }

            $phone = $row['phone'];
            $student = Student::where('phone', $phone)->first();

            if ($student) {
                // Check if the student already has the lesson
                $existingPayment = Payment::where('student_id', $student->id)
                    ->where('lesson_id', $this->lesson->id)
                    ->where('payment_status', Payment::PAYMENT_STATUS['approved'])
                    ->exists();

                if (!$existingPayment) {
                    Payment::create([
                        'student_id' => $student->id,
                        'lesson_id' => $this->lesson->id,
                        'payment_method' => 'gift',
                        'payment_status' => Payment::PAYMENT_STATUS['approved'],
                        'amount' => 0,
                    ]);
                    $this->importedCount++;
                }
            } else {
                $this->notFoundPhones[] = $phone;
            }
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
