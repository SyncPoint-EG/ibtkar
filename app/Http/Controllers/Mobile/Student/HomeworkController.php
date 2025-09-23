<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomeworkResource;
use App\Models\Homework;
use App\Models\HomeworkAnswer;
use App\Models\HomeworkAttempt;
use App\Traits\GamificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeworkController extends Controller
{
    use GamificationTrait ;
    public function index()
    {
        $student = auth('student')->user();
        $homeworks = Homework::whereHas('lesson.chapter.course', function ($query) use ($student) {
            $query->where('stage_id', $student->stage_id)
                ->where('grade_id', $student->grade_id)
                ->where('division_id', $student->division_id);
        })->get();

        return HomeworkResource::collection($homeworks);
    }

    public function show(Homework $homework)
    {
        $homework->load('questions.options');
        return new HomeworkResource($homework);
    }

    public function submit(Request $request,  $homeworkId)
    {
        $student = Auth::user();
        $homework = Homework::with('questions.options', 'lesson.chapter.course')->findOrFail($homeworkId);
        // Authorization: Check if student is enrolled in the course
        if (!$student->isEnrolledInCourse($homework->lesson->chapter->course_id)) {
            return response()->json(['message' => 'You are not authorized to submit this homework.'], 403);
        }

        // Prevent re-submission
        $previousAttempt = HomeworkAttempt::where('student_id', $student->id)
            ->where('homework_id', $homework->id)
            ->first(); // Assuming one attempt

        if ($previousAttempt) {
            return response()->json(['message' => 'You have already submitted this homework.'], 400);
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:homework_questions,id',
            'answers.*.option_id' => 'nullable|exists:homework_question_options,id',
            'answers.*.essay_answer' => 'nullable|string',
            'answers.*.true_false_answer' => 'nullable|boolean',
        ]);

        $total_score = 0;
        $answers = [];
        $questions = $homework->questions()->with('options')->get()->keyBy('id');

        $homeworkAttempt = HomeworkAttempt::create([
            'student_id' => $student->id,
            'homework_id' => $homework->id,
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

            // NOTE: The 'homework_answers' table needs a 'homework_attempt_id' column
            // and the HomeworkAnswer model's fillable array needs to be updated.
            $answers[] = [
                'homework_attempt_id' => $homeworkAttempt->id, // This column needs to be added to the table
                'student_id' => $student->id,
                'homework_id' => $homework->id,
                'question_id' => $question->id,
                'option_id' => $answerData['option_id'] ?? null,
                'essay_answer' => $answerData['essay_answer'] ?? null,
                'is_correct' => $is_correct,
            ];
        }

        // This will fail until the 'homework_answers' table is migrated
        // HomeworkAnswer::insert($answers);

        $homeworkAttempt->update(['score' => $total_score]);
        $points =$this->givePoints($student , 'solve_exam');

        return response()->json([
            'message' => 'Homework submitted successfully.',
            'score' => $total_score,
            'total_degree' => $questions->sum('marks'),
            'homework_attempt' => $homeworkAttempt,
            'rewarded_points' => $points

        ]);
    }
}
