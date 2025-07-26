<?php
// app/Http/Controllers/ExamTakingController.php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamTakingController extends Controller
{
    public function takeExam(Exam $exam)
    {
        // Check if exam is available
        if (!$exam->isAvailable()) {
            return redirect()->back()->with('error', 'This exam is not currently available.');
        }

        // Check if user has already taken this exam
        $existingAttempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', Auth::id())
            ->where('is_submitted', true)
            ->first();

        if ($existingAttempt) {
            return redirect()->route('exam-attempts.results', $existingAttempt)
                ->with('info', 'You have already taken this exam.');
        }

        // Check for ongoing attempt
        $ongoingAttempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', Auth::id())
            ->where('is_submitted', false)
            ->first();

        if (!$ongoingAttempt) {
            // Create new attempt
            $ongoingAttempt = ExamAttempt::create([
                'exam_id' => $exam->id,
                'user_id' => Auth::id(),
                'started_at' => now(),
                'total_marks' => $exam->total_marks,
                'is_submitted' => false,
            ]);
        }

        // Check if time is up
        $timeElapsed = now()->diffInMinutes($ongoingAttempt->started_at);
        if ($timeElapsed >= $exam->duration_minutes) {
            return $this->autoSubmitExam($ongoingAttempt);
        }

        $exam->load(['questions.options']);
        $timeRemaining = $exam->duration_minutes - $timeElapsed;

        // Get existing answers
        $existingAnswers = $ongoingAttempt->answers()
            ->with('selectedOption')
            ->get()
            ->keyBy('question_id');

        return view('dashboard.exams.take', compact('exam', 'ongoingAttempt', 'timeRemaining', 'existingAnswers'));
    }

    public function submitExam(Request $request, Exam $exam)
    {
        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', Auth::id())
            ->where('is_submitted', false)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Process answers
            $totalScore = 0;

            foreach ($exam->questions as $question) {
                $answerKey = "question_{$question->id}";
                $userAnswer = $request->input($answerKey);

                // Delete existing answer if any
                ExamAnswer::where('exam_attempt_id', $attempt->id)
                    ->where('question_id', $question->id)
                    ->delete();

                $answer = new ExamAnswer([
                    'exam_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                ]);

                $score = 0;

                switch ($question->question_type) {
                    case 'true_false':
                        if ($userAnswer !== null) {
                            $answer->true_false_answer = (bool) $userAnswer;
                            $correctOption = $question->options()->where('is_correct', true)->first();
                            if ($correctOption && $correctOption->option_text === ($userAnswer ? 'True' : 'False')) {
                                $score = $question->marks;
                            }
                        }
                        break;

                    case 'multiple_choice':
                        if ($userAnswer !== null) {
                            $answer->selected_option_id = $userAnswer;
                            $selectedOption = $question->options()->find($userAnswer);
                            if ($selectedOption && $selectedOption->is_correct) {
                                $score = $question->marks;
                            }
                        }
                        break;

                    case 'essay':
                        $answer->essay_answer = $userAnswer;
                        // Essay questions require manual grading
                        $score = null;
                        break;
                }

                $answer->marks_awarded = $score;
                $answer->save();

                if ($score !== null) {
                    $totalScore += $score;
                }
            }

            // Update attempt
            $attempt->update([
                'completed_at' => now(),
                'score' => $totalScore,
                'is_submitted' => true,
            ]);

            DB::commit();

            return redirect()->route('exam-attempts.results', $attempt)
                ->with('success', 'Exam submitted successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error submitting exam: ' . $e->getMessage());
        }
    }

    public function showResults(ExamAttempt $attempt)
    {
        // Check if user owns this attempt
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        $attempt->load([
            'exam.questions.options',
            'answers.question.options',
            'answers.selectedOption'
        ]);

        return view('dashboard.exams.results', compact('attempt'));
    }

    private function autoSubmitExam(ExamAttempt $attempt)
    {
        // Auto-submit the exam when time is up
        $attempt->update([
            'completed_at' => now(),
            'is_submitted' => true,
        ]);

        return redirect()->route('exam-attempts.results', $attempt)
            ->with('warning', 'Time is up! Your exam has been automatically submitted.');
    }

    public function saveAnswer(Request $request, Exam $exam)
    {
        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', Auth::id())
            ->where('is_submitted', false)
            ->firstOrFail();

        $questionId = $request->input('question_id');
        $answer = $request->input('answer');

        $question = $exam->questions()->findOrFail($questionId);

        // Update or create answer
        $examAnswer = ExamAnswer::updateOrCreate(
            [
                'exam_attempt_id' => $attempt->id,
                'question_id' => $questionId,
            ],
            [
                'selected_option_id' => $question->question_type === 'multiple_choice' ? $answer : null,
                'true_false_answer' => $question->question_type === 'true_false' ? (bool) $answer : null,
                'essay_answer' => $question->question_type === 'essay' ? $answer : null,
            ]
        );

        return response()->json(['success' => true, 'message' => 'Answer saved']);
    }
}
