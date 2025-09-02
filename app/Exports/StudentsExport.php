<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Student::with('guardian', 'district', 'center', 'stage', 'grade', 'division');

        if (!empty($this->filters['name'])) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->filters['name'] . '%')
                    ->orWhere('last_name', 'like', '%' . $this->filters['name'] . '%');
            });
        }

        if (!empty($this->filters['phone'])) {
            $query->where('phone', 'like', '%' . $this->filters['phone'] . '%');
        }

        if (!empty($this->filters['governorate_id'])) {
            $query->where('governorate_id', $this->filters['governorate_id']);
        }

        if (!empty($this->filters['center_id'])) {
            $query->where('center_id', $this->filters['center_id']);
        }

        if (!empty($this->filters['stage_id'])) {
            $query->where('stage_id', $this->filters['stage_id']);
        }

        if (!empty($this->filters['grade_id'])) {
            $query->where('grade_id', $this->filters['grade_id']);
        }

        if (!empty($this->filters['division_id'])) {
            $query->where('division_id', $this->filters['division_id']);
        }

        if (!empty($this->filters['education_type_id'])) {
            $query->where('education_type_id', $this->filters['education_type_id']);
        }

        if (isset($this->filters['status']) && $this->filters['status'] !== '') {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['gender'])) {
            $query->where('gender', $this->filters['gender']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Phone',
            'Guardian Phone',
            'District',
            'Center',
            'Stage',
            'Grade',
            'Division',
            'Gender',
            'Birth Date',
            'Status',
            'Referral Code',
            'Points',
            'Purchased Lessons',
        ];
    }

    public function map($student): array
    {
        return [
            $student->first_name,
            $student->last_name,
            $student->phone,
            $student->guardian?->phone,
            $student->district?->name,
            $student->center?->name,
            $student->stage?->name,
            $student->grade?->name,
            $student->division?->name,
            $student->gender,
            $student->birth_date ? $student->birth_date->format('Y-m-d') : '',
            $student->status ? 'Active' : 'Inactive',
            $student->referral_code,
            $student->points,
            $student->purchased_lessons_count,
        ];
    }
}