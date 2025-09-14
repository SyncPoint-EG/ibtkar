<?php

namespace App\Exports;

use App\Models\Code;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CodesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Code::with('teacher');

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

        return $query->get();
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
            'Created At',
        ];
    }

    public function map($code): array
    {
        return [
            $code->code,
            $code->for,
            $code->number_of_uses,
            $code->expires_at ? $code->expires_at->format('Y-m-d') : '',
            $code->code_classification ?? 'N/A',
            $code->teacher->name ?? '',
            $code->created_at->format('Y-m-d H:i:s'),
        ];
    }
}