<?php

namespace App\Exports;

use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TeacherStudentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $teacher;

    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    public function collection()
    {
        $courseIds = $this->teacher->courses()->pluck('id');
        $chapterIds = Chapter::whereIn('course_id', $courseIds)->pluck('id');
        $lessonIds = Lesson::whereIn('chapter_id', $chapterIds)->pluck('id');

        $studentIds = Payment::where('payment_status', Payment::PAYMENT_STATUS['approved'])
            ->where(function ($query) use ($courseIds, $chapterIds, $lessonIds) {
                $query->whereIn('course_id', $courseIds)
                    ->orWhereIn('chapter_id', $chapterIds)
                    ->orWhereIn('lesson_id', $lessonIds);
            })
            ->pluck('student_id')->unique();

        return Student::whereIn('id', $studentIds)->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Phone',
            'Grade',
        ];
    }

    public function map($student): array
    {
        return [
            $student->id,
            $student->name,
            $student->phone,
            $student->grade->name ?? '',
        ];
    }
}
