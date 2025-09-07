<?php

namespace App\Services;

use App\Models\ExamAttempt;
use Illuminate\Support\Collection;

class ExamRankingService
{
    public function getTopStudents(int $examId, int $limit = 10): Collection
    {
        return ExamAttempt::with('student')
            ->where('exam_id', $examId)
            ->orderByDesc('score')
            ->limit($limit)
            ->get();
    }
}
