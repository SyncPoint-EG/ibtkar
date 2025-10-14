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
    protected $filters;

    public function __construct(Teacher $teacher, array $filters = [])
    {
        $this->teacher = $teacher;
        $this->filters = $filters;
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

        $studentsQuery = Student::whereIn('id', $studentIds);

        // Apply filters
        if (!empty($this->filters['search'])) {
            $studentsQuery->where(function ($q) {
                $q->where('name', 'like', '%' . $this->filters['search'] . '%')
                    ->orWhere('phone', 'like', '%' . $this->filters['search'] . '%');
            });
        }

        if (!empty($this->filters['stage_id'])) {
            $studentsQuery->whereHas('grade.stage', function ($q) {
                $q->where('id', $this->filters['stage_id']);
            });
        }

        if (!empty($this->filters['grade_id'])) {
            $studentsQuery->where('grade_id', $this->filters['grade_id']);
        }

        if (!empty($this->filters['division_id'])) {
            $studentsQuery->where('division_id', $this->filters['division_id']);
        }

        return $studentsQuery->get();
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