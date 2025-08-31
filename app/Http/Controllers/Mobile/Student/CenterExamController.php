<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\CenterExamResource;
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

        return CenterExamResource::collection($centerExams);
    }

    public function show(CenterExam $centerExam)
    {
        $centerExam->load('questions.options');
        return new CenterExamResource($centerExam);
    }

    public function submit(Request $request, CenterExam $centerExam)
    {
        $student = Auth::user();

        // Authorization: Check if student is eligible for this exam
        if ($centerExam->stage_id != $student->stage_id || $centerExam->grade_id != $student->grade_id || $centerExam->division_id != $student->division_id) {
            return response()->json(['message' => 'You are not authorized to submit this exam.'], 403);
        }

        // Prevent re-submission
        $previousAttempt = CenterExamAttempt::where('student_id', $student->id)
            ->where('center_exam_id', $centerExam->id)
            ->first(); // Assuming one attempt

        if ($previousAttempt) {
            return response()->json(['message' => 'You have already submitted this exam.'], 400);
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:center_exam_questions,id',
            'answers.*.option_id' => 'nullable|exists:center_exam_question_options,id',
            'answers.*.essay_answer' => 'nullable|string',
            'answers.*.true_false_answer' => 'nullable|boolean',
        ]);

        $total_score = 0;
        $answers = [];
        $questions = $centerExam->questions()->with('options')->get()->keyBy('id');

        $centerExamAttempt = CenterExamAttempt::create([
            'student_id' => $student->id,
            'center_exam_id' => $centerExam->id,
            'score' => 0, // will be updated
        ]);

        foreach ($validated['answers'] as $answerData) {
            $question = $questions->get($answerData['question_id']);
            if (!$question) {
                continue;
            }

            $is_correct = false;
            if ($question->question_type == 'multiple_choice' || $question->question_type == 'true_false') {
                $correctOption = $question->options->where('is_correct', true)->first();
                if ($correctOption && $correctOption->id == ($answerData['option_id'] ?? null)) {
                    $total_score += $question->marks;
                    $is_correct = true;
                }
            }

            $answers[] = [
                'center_exam_attempt_id' => $centerExamAttempt->id,
                'student_id' => $student->id,
                'center_exam_id' => $centerExam->id,
                'question_id' => $question->id,
                'option_id' => $answerData['option_id'] ?? null,
                'essay_answer' => $answerData['essay_answer'] ?? null,
                'is_correct' => $is_correct,
            ];
        }

        CenterExamAnswer::insert($answers);

        $centerExamAttempt->update(['score' => $total_score]);

        return response()->json([
            'message' => 'Center exam submitted successfully.',
            'score' => $total_score,
            'total_degree' => $questions->sum('marks'),
            'center_exam_attempt' => $centerExamAttempt
        ]);
    }
}
