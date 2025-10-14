<?php

// app/Http/Controllers/ExamController.php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExamRequest;
use App\Models\Exam;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('lesson')->paginate(10);

        return view('dashboard.exams.index', compact('exams'));
    }

    public function create()
    {
        $teachers = \App\Models\Teacher::all();

        return view('dashboard.exams.create', compact('teachers'));
    }

    public function store(ExamRequest $request)
    {
        $validated = $request->validated();

        // Handle boolean for is_active checkbox
        $validated['is_active'] = $request->has('is_active');

        if ($request->input('exam_type') === 'lesson') {
            $validated['course_id'] = null;
            $validated['teacher_id'] = null;
        } else { // exam_type is 'teacher'
            $validated['lesson_id'] = null;
        }

        $exam = Exam::create($validated);

        return redirect()->route('exams.show', $exam)
            ->with('success', 'Exam created successfully. You can now add questions.');
    }

    public function show(Exam $exam)
    {
        $exam->load(['lesson', 'questions.options']);

        return view('dashboard.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        $lessons = Lesson::all();
        $teachers = \App\Models\Teacher::all();

        return view('dashboard.exams.edit', compact('exam', 'lessons', 'teachers'));
    }

    public function update(ExamRequest $request, Exam $exam)
    {
        $validated = $request->validated();

        if ($request->exam_for === 'lesson') {
            $validated['teacher_id'] = null;
            $validated['course_id'] = null;
        } else {
            $validated['lesson_id'] = null;
        }

        if ($request->is_active) {
            $validated['is_active'] = 1;
        } else {
            $validated['is_active'] = 0;
        }
        $exam->update($validated);

        return redirect()->route('exams.show', $exam)
            ->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()->route('exams.index')
            ->with('success', 'Exam deleted successfully.');
    }

    public function addQuestion(Request $request, Exam $exam)
    {
        //        return $request ;
        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:true_false,multiple_choice,essay',
            'marks' => 'required|integer|min:0',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*' => 'required_if:question_type,multiple_choice',
            'correct_answer' => 'required_if:question_type,multiple_choice|integer',
            'true_false_answer' => 'required_if:question_type,true_false|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'correct_essay_answer' => 'required_if:question_type,essay|nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $questionData = [
                'exam_id' => $exam->id,
                'question_text' => $request->question_text,
                'question_type' => $request->question_type,
                'marks' => $request->marks,
                'order' => $exam->questions()->count() + 1,
            ];

            if ($request->hasFile('image')) {
                $questionData['image'] = $request->file('image');
            }

            if ($request->question_type === 'essay') {
                $questionData['correct_essay_answer'] = $request->correct_essay_answer;
            }

            $question = Question::create($questionData);

            if ($request->question_type === 'multiple_choice') {
                foreach ($request->options as $index => $optionText) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $optionText,
                        'is_correct' => $index == $request->correct_answer,
                    ]);
                }
            } elseif ($request->question_type === 'true_false') {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => 'True',
                    'is_correct' => $request->true_false_answer == 1,
                ]);
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => 'False',
                    'is_correct' => $request->true_false_answer == 0,
                ]);
            }

            // Update exam total marks
            $exam->update([
                'total_marks' => $exam->questions()->sum('marks'),
            ]);

            DB::commit();

            return redirect()->route('exams.show', $exam)
                ->with('success', 'Question added successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Error adding question: '.$e->getMessage());
        }
    }

    public function getQuestion(Request $request, Exam $exam, Question $question)
    {
        // Ensure the question belongs to this exam
        if ($question->exam_id !== $exam->id) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Question not found in this exam.'], 404);
            }

            return back()->with('error', 'Question not found in this exam.');
        }

        if ($request->ajax()) {
            $questionData = [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'question_type' => $question->question_type,
                'marks' => $question->marks,
                'image' => $question->image,
                'correct_essay_answer' => $question->correct_essay_answer,
                'options' => $question->options->map(function ($option) {
                    return [
                        'id' => $option->id,
                        'option_text' => $option->option_text,
                        'is_correct' => $option->is_correct,
                    ];
                }),
            ];

            return response()->json(['success' => true, 'question' => $questionData]);
        }

        return back();
    }

    public function updateQuestion(Request $request, Exam $exam, Question $question)
    {
        // Ensure the question belongs to this exam
        if ($question->exam_id !== $exam->id) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Question not found in this exam.'], 404);
            }

            return back()->with('error', 'Question not found in this exam.');
        }

        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:true_false,multiple_choice,essay',
            'marks' => 'required|integer|min:0',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*' => 'required_if:question_type,multiple_choice',
            'correct_answer' => 'required_if:question_type,multiple_choice|integer',
            'true_false_answer' => 'required_if:question_type,true_false|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'correct_essay_answer' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $questionData = [
                'question_text' => $request->question_text,
                'question_type' => $request->question_type,
                'marks' => $request->marks,
            ];

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($question->image && Storage::exists($question->image)) {
                    Storage::delete($question->image);
                }
                $questionData['image'] = $request->file('image')->store('questions', 'public');
            }

            if ($request->question_type === 'essay') {
                $questionData['correct_essay_answer'] = $request->correct_essay_answer;
            } else {
                $questionData['correct_essay_answer'] = null;
            }

            $question->update($questionData);

            // Delete existing options
            $question->options()->delete();

            // Create new options based on type
            if ($request->question_type === 'multiple_choice') {
                foreach ($request->options as $index => $optionText) {
                    if (! empty(trim($optionText))) {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'option_text' => $optionText,
                            'is_correct' => $index == $request->correct_answer,
                        ]);
                    }
                }
            } elseif ($request->question_type === 'true_false') {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => 'True',
                    'is_correct' => $request->true_false_answer == 1,
                ]);
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => 'False',
                    'is_correct' => $request->true_false_answer == 0,
                ]);
            }

            // Update exam total marks
            $exam->update([
                'total_marks' => $exam->questions()->sum('marks'),
            ]);

            DB::commit();

            if ($request->ajax()) {
                // Return updated question data
                $updatedQuestion = $question->fresh(['options']);

                return response()->json([
                    'success' => true,
                    'message' => 'Question updated successfully.',
                    'question' => $updatedQuestion,
                    'total_marks' => $exam->fresh()->total_marks,
                ]);
            }

            return redirect()->route('exams.show', $exam)
                ->with('success', 'Question updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error updating question: '.$e->getMessage()]);
            }

            return back()->with('error', 'Error updating question: '.$e->getMessage());
        }
    }

    public function removeQuestion(Exam $exam, Question $question)
    {
        if ($question->exam_id !== $exam->id) {
            return back()->with('error', 'Question does not belong to this exam.');
        }

        DB::beginTransaction();
        try {
            // First delete the question's options if they exist
            if ($question->options()) {
                $question->options()->delete();
            }

            // Delete the question
            $question->delete();

            // Refresh the exam model to get updated relationships
            $exam->refresh();

            // Update exam total marks - make sure to only count non-deleted questions
            $totalMarks = $exam->questions()->sum('marks');

            $exam->update([
                'total_marks' => $totalMarks,
            ]);

            DB::commit();

            return redirect()->route('exams.show', $exam)
                ->with('success', 'Question removed successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error removing question: '.$e->getMessage());

            return back()->with('error', 'Error removing question: '.$e->getMessage());
        }
    }

    public function toggleActive(Exam $exam)
    {
        $exam->update(['is_active' => ! $exam->is_active]);

        $status = $exam->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Exam {$status} successfully.");
    }

    public function submissions(Exam $exam)
    {
        $exam->load('attempts.student', 'attempts.answers.question.options');

        return view('dashboard.exams.submissions', compact('exam'));
    }
}
