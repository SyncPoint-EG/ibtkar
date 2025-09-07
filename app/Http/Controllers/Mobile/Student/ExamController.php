<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExamResource;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Services\ExamAttemptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    protected ExamAttemptService $examAttemptService;

    public function __construct(ExamAttemptService $examAttemptService)
    {
        $this->examAttemptService = $examAttemptService;
    }
    public function index()
    {
        $student = auth('student')->user();
        $exams = Exam::query()->whereHas('lesson.chapter.course', function ($query) use ($student) {
            $query->where('stage_id', $student->stage_id)
                ->where('grade_id', $student->grade_id)
                ->where('division_id', $student->division_id);
        })->orWhereHas('course', function ($query) use ($student) {
            $query->where('stage_id', $student->stage_id)
                ->where('grade_id', $student->grade_id)
                ->where('division_id', $student->division_id);
        })->get();

        return ExamResource::collection($exams);
    }

    public function show($examId)
    {
        $exam = Exam::findOrFail($examId);
        $exam->load('questions.options');
        return new ExamResource($exam);
    }

    public function submit(Request $request, $exam_id)
    {
        $student = Auth::user();
        $exam = Exam::with('questions.options')->findOrFail($exam_id);

        // Authorization: Check if student is eligible for this exam
        $isEligible = $exam->lesson ? $student->isEnrolledInLesson($exam->lesson) : $student->isEnrolledInCourse($exam->course_id);
        if (!$isEligible) {
            return response()->json(['message' => 'You are not authorized to submit this exam.(purchase the course first)'], 403);
        }

        // Prevent re-submission
        $previousAttempt = ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->where('is_submitted', true)
            ->first();

        if ($previousAttempt && $previousAttempt->is_passed) {
            return response()->json(['message' => 'You have already submitted this exam.'], 400);
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.option_id' => 'nullable|exists:question_options,id',
            'answers.*.essay_answer' => 'nullable|string',
            'answers.*.true_false_answer' => 'nullable|boolean',
        ]);

        $total_score = 0;
        $answers = [];
        $questions = $exam->questions->keyBy('id');

        $examAttempt = ExamAttempt::create([
            'student_id' => $student->id,
            'exam_id' => $exam->id,
            'score' => 0, // will be updated
            'total_marks' => $exam->questions->sum('marks'),
            'started_at' => now(), // Assuming the submission starts now
        ]);

        foreach ($validated['answers'] as $answerData) {
            $question = $questions->get($answerData['question_id']);
            if (!$question) {
                continue;
            }

            $marks_awarded = 0;
            $answer = [
                'exam_attempt_id' => $examAttempt->id,
                'question_id' => $question->id,
                'essay_answer' => $answerData['essay_answer'] ?? null,
                'selected_option_id' => null,
                'true_false_answer' => null,
                'marks_awarded' => 0,
            ];

            if ($question->question_type == 'multiple_choice') {
                $correctOption = $question->options->where('is_correct', true)->first();
                if ($correctOption && $correctOption->id == ($answerData['option_id'] ?? null)) {
                    $marks_awarded = $question->marks;
                }
                $answer['selected_option_id'] = $answerData['option_id'] ?? null;
            } elseif ($question->question_type == 'true_false') {
                $correct_answer = (bool) $question->options->where('is_correct', true)->value('option_text');
                if (isset($answerData['true_false_answer']) && $correct_answer === $answerData['true_false_answer']) {
                    $marks_awarded = $question->marks;
                }
                $answer['true_false_answer'] = $answerData['true_false_answer'] ?? null;
            }
            // For essay questions, marks will be awarded manually later.

            $answer['marks_awarded'] = $marks_awarded;
            $total_score += $marks_awarded;
            $answers[] = $answer;
        }

        ExamAnswer::insert($answers);

        $examAttempt->update([
            'score' => $total_score,
            'completed_at' => now(),
            'is_submitted' => true,
        ]);

        $this->examAttemptService->checkIfPassed($examAttempt);

        return response()->json([
            'message' => 'Exam submitted successfully.',
            'score' => $total_score,
            'total_degree' => $examAttempt->total_marks,
            'exam_attempt' => $examAttempt
        ]);
    }
}
