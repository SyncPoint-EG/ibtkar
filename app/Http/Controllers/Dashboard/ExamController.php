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

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('lesson')->paginate(10);
        return view('dashboard.exams.index', compact('exams'));
    }

    public function create()
    {
        $lessons = Lesson::all();
        return view('dashboard.exams.create', compact('lessons'));
    }

    public function store(ExamRequest $request)
    {
//        $request->validate([
//            'title' => 'required|string|max:255',
//            'description' => 'nullable|string',
//            'lesson_id' => 'nullable|exists:lessons,id',
//            'duration_minutes' => 'required|integer|min:1',
//            'start_date' => 'nullable|date',
//            'end_date' => 'nullable|date|after:start_date',
//        ]);
        $validated = $request->validated();
        $validated['total_marks'] = 0 ;
        if($request->is_active){
            $validated['is_active'] = 1;
        }else{
            $validated['is_active'] = 0;
        }
        $exam = Exam::create($validated);

        return redirect()->route('exams.show', $exam)
            ->with('success', 'Exam created successfully.');
    }

    public function show(Exam $exam)
    {
        $exam->load(['lesson', 'questions.options']);
        return view('dashboard.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        $lessons = Lesson::all();
        return view('dashboard.exams.edit', compact('exam', 'lessons'));
    }

    public function update(ExamRequest $request, Exam $exam)
    {
//        $request->validate([
//            'title' => 'required|string|max:255',
//            'description' => 'nullable|string',
//            'lesson_id' => 'required|exists:lessons,id',
//            'duration_minutes' => 'required|integer|min:1',
//            'start_date' => 'nullable|date',
//            'end_date' => 'nullable|date|after:start_date',
//        ]);
        $validated = $request->validated();

        if($request->is_active){
            $validated['is_active'] = 1;
        }else{
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
                'total_marks' => $exam->questions()->sum('marks')
            ]);

            DB::commit();
            return redirect()->route('exams.show', $exam)
                ->with('success', 'Question added successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error adding question: ' . $e->getMessage());
        }
    }

    public function removeQuestion(Exam $exam, Question $question)
    {
        if ($question->exam_id !== $exam->id) {
            return back()->with('error', 'Question does not belong to this exam.');
        }

        $question->delete();

        // Update exam total marks
        $exam->update([
            'total_marks' => $exam->questions()->sum('marks')
        ]);

        return redirect()->route('exams.show', $exam)
            ->with('success', 'Question removed successfully.');
    }

    public function toggleActive(Exam $exam)
    {
        $exam->update(['is_active' => !$exam->is_active]);

        $status = $exam->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Exam {$status} successfully.");
    }
}
