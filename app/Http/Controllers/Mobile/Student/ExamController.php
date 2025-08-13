<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $exams = Exam::whereHas('lesson.chapter.course', function ($query) use ($student) {
            $query->where('stage_id', $student->stage_id)
                ->where('grade_id', $student->grade_id)
                ->where('division_id', $student->division_id);
        })->get();

        return response()->json($exams);
    }

    public function show(Exam $exam)
    {
        $exam->load('questions.options');
        return response()->json($exam);
    }

    public function submit(Request $request, Exam $exam)
    {
        $student = Auth::user();
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.option_id' => 'nullable|exists:question_options,id',
            'answers.*.essay_answer' => 'nullable|string',
        ]);

        $total_score = 0;
        $examAttempt = ExamAttempt::create([
            'student_id' => $student->id,
            'exam_id' => $exam->id,
            'score' => 0, // will be updated
        ]);

        foreach ($validated['answers'] as $answerData) {
            $question = $exam->questions()->find($answerData['question_id']);
            if (!$question) {
                continue;
            }

            $is_correct = false;
            if ($question->type == 'mcq' || $question->type == 'true_false') {
                $correctOption = $question->options()->where('is_correct', true)->first();
                if ($correctOption && $correctOption->id == $answerData['option_id']) {
                    $total_score += $question->degree;
                    $is_correct = true;
                }
            }
            // For essay questions, manual correction is assumed to be needed.
            // Here we just store the answer. The score for essay is not added automatically.

            ExamAnswer::create([
                'exam_attempt_id' => $examAttempt->id,
                'student_id' => $student->id,
                'exam_id' => $exam->id,
                'question_id' => $question->id,
                'option_id' => $answerData['option_id'] ?? null,
                'essay_answer' => $answerData['essay_answer'] ?? null,
                'is_correct' => $is_correct,
            ]);
        }

        $examAttempt->update(['score' => $total_score]);

        return response()->json([
            'message' => 'Exam submitted successfully.',
            'score' => $total_score,
            'total_degree' => $exam->questions()->sum('degree'),
            'exam_attempt' => $examAttempt
        ]);
    }
}
