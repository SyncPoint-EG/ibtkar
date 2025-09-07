<?php

namespace App\Services;

use App\Models\ExamAttempt;

class ExamAttemptService
{
    public function checkIfPassed(ExamAttempt $examAttempt): void
    {
        $exam = $examAttempt->exam;
        if ($exam->pass_degree > 0) {
            $isPassed = $examAttempt->score >= $exam->pass_degree;
            $examAttempt->update(['is_passed' => $isPassed]);
        }
    }
}
