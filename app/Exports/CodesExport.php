<?php

namespace App\Exports;

use App\Models\Code;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CodesExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading, ShouldQueue
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        $query = Code::with(['teacher', 'payment.student', 'payment.course', 'payment.chapter', 'payment.lesson']);

        if (isset($this->filters['teacher_id']) && !empty($this->filters['teacher_id'])) {
            $query->where('teacher_id', $this->filters['teacher_id']);
        }

        if (isset($this->filters['expires_at']) && !empty($this->filters['expires_at'])) {
            $query->whereDate('expires_at', $this->filters['expires_at']);
        }

        if (isset($this->filters['for']) && !empty($this->filters['for'])) {
            $query->where('for', $this->filters['for']);
        }

        if (isset($this->filters['created_at_from']) && !empty($this->filters['created_at_from'])) {
            $query->where('created_at', '>=', $this->filters['created_at_from']);
        }

        if (isset($this->filters['created_at_to']) && !empty($this->filters['created_at_to'])) {
            $query->where('created_at', '<=', $this->filters['created_at_to']);
        }

        if (isset($this->filters['code']) && !empty($this->filters['code'])) {
            $query->where('code', $this->filters['code']);
        }

        if (isset($this->filters['code_classification']) && !empty($this->filters['code_classification'])) {
            $query->where('code_classification', $this->filters['code_classification']);
        }

        return $query;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function headings(): array
    {
        return [
            'Code',
            'For',
            'Number of Uses',
            'Expires At',
            'Code Classification',
            'Teacher',
            'Student Name',
            'Student Phone',
            'Used In',
            'Used At',
            'Price',
            'Created At',
        ];
    }

    public function map($code): array
    {
        $for = $code->for;
        return [
            $code->code,
            $code->for,
            $code->number_of_uses,
            $code->expires_at ? $code->expires_at->format('Y-m-d') : '',
            $code->code_classification ?? 'N/A',
            $code->teacher->name ?? '',
            $code->payment->student->name ?? '',
            $code->payment->student->phone ?? '',
            $code->payment && $code->payment->$for ? $code->payment->$for->name : 'N/A',
            $code->payment->created_at ?? 'N/A',
            $code->price ?? 'N/A',
            $code->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
