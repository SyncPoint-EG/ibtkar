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

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Code',
            'For',
            'Number of Uses',
            'Expires At',
            'Teacher',
        ];
    }

    public function map($code): array
    { 
        return [
            $code->code,
            $code->for,
            $code->number_of_uses,
            $code->expires_at ? $code->expires_at->format('Y-m-d') : '',
            $code->teacher->name ?? '',
        ];
    }
}
