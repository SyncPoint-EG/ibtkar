<?php

namespace App\Http\Controllers\Mobile\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomeworkResource;
use App\Models\Homework;
use App\Models\HomeworkAnswer;
use App\Models\HomeworkAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeworkController extends Controller
{
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

    public function submit(Request $request, Homework $homework)
    {
        $student = auth('student')->user();
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:homework_questions,id',
            'answers.*.option_id' => 'nullable|exists:homework_question_options,id',
            'answers.*.essay_answer' => 'nullable|string',
        ]);

        $total_score = 0;

        $homeworkAttempt = HomeworkAttempt::create([
            'student_id' => $student->id,
            'homework_id' => $homework->id,
            'score' => 0, // will be updated
        ]);

        foreach ($validated['answers'] as $answerData) {
            $question = $homework->questions()->find($answerData['question_id']);
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

            HomeworkAnswer::create([
                'homework_attempt_id' => $homeworkAttempt->id, // This column needs to be added
                'student_id' => $student->id,
                'homework_id' => $homework->id,
                'question_id' => $question->id,
                'option_id' => $answerData['option_id'] ?? null,
                'essay_answer' => $answerData['essay_answer'] ?? null,
                'is_correct' => $is_correct,
            ]);
        }

        $homeworkAttempt->update(['score' => $total_score]);

        return response()->json([
            'message' => 'Homework submitted successfully.',
            'score' => $total_score,
            'total_degree' => $homework->questions()->sum('degree'),
            'homework_attempt' => $homeworkAttempt
        ]);
    }
}
