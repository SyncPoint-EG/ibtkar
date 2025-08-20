<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Models\CenterExam;
use App\Models\CenterExamAnswer;
use App\Models\CenterExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CenterExamController extends Controller
{
    public function index()
    {
        $student = auth('student')->user();
        $centerExams = CenterExam::where('stage_id', $student->stage_id)
            ->where('grade_id', $student->grade_id)
            ->where('division_id', $student->division_id)
            ->where('start_time','>' ,now());
        if(\request()->center_id){
             $centerExams = $centerExams->where('center_id', \request()->center_id);
        }
        if(\request()->teacher_id){
            $centerExams = $centerExams->where('teacher_id', \request()->teacher_id);
        }
        $centerExams = $centerExams->get();

        return response()->json($centerExams);
    }

    public function show(CenterExam $centerExam)
    {
        $centerExam->load('questions.options');
        return response()->json($centerExam);
    }

    public function submit(Request $request, CenterExam $centerExam)
    {
        $student = auth('student')->user();
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:center_exam_questions,id',
            'answers.*.option_id' => 'nullable|exists:center_exam_question_options,id',
            'answers.*.essay_answer' => 'nullable|string',
        ]);

        $total_score = 0;

        $centerExamAttempt = CenterExamAttempt::create([
            'student_id' => $student->id,
            'center_exam_id' => $centerExam->id,
            'score' => 0, // will be updated
        ]);

        foreach ($validated['answers'] as $answerData) {
            $question = $centerExam->questions()->find($answerData['question_id']);
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

            CenterExamAnswer::create([
                'center_exam_attempt_id' => $centerExamAttempt->id,
                'student_id' => $student->id,
                'center_exam_id' => $centerExam->id,
                'question_id' => $question->id,
                'option_id' => $answerData['option_id'] ?? null,
                'essay_answer' => $answerData['essay_answer'] ?? null,
                'is_correct' => $is_correct,
            ]);
        }

        $centerExamAttempt->update(['score' => $total_score]);

        return response()->json([
            'message' => 'Center exam submitted successfully.',
            'score' => $total_score,
            'total_degree' => $centerExam->questions()->sum('degree'),
            'center_exam_attempt' => $centerExamAttempt
        ]);
    }
}
