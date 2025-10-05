<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\HomeworkQuestion;
use Illuminate\Http\Request;

class HomeworkQuestionController extends Controller
{
    public function store(Request $request, Homework $homework)
    {
        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:true_false,multiple_choice,essay',
            'marks' => 'required|integer|min:1',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*' => 'required_if:question_type,multiple_choice|string',
            'correct_option' => 'required_if:question_type,multiple_choice|integer',
            'correct_answer' => 'required_if:question_type,true_false|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $questionData = [
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'marks' => $request->marks,
            'order' => $homework->questions()->count() + 1,
        ];

        if ($request->hasFile('image')) {
            $questionData['image'] = $request->file('image');
        }

        $question = $homework->questions()->create($questionData);

        // Handle different question types
        if ($request->question_type === 'multiple_choice') {
            foreach ($request->options as $index => $optionText) {
                $question->options()->create([
                    'option_text' => $optionText,
                    'is_correct' => ($index == $request->correct_option),
                    'order' => $index + 1,
                ]);
            }
        } elseif ($request->question_type === 'true_false') {
            $question->options()->create([
                'option_text' => 'True',
                'is_correct' => $request->correct_answer == 1,
                'order' => 1,
            ]);
            $question->options()->create([
                'option_text' => 'False',
                'is_correct' => $request->correct_answer == 0,
                'order' => 2,
            ]);
        }

        // Update homework total marks
        $homework->update(['total_marks' => $homework->questions()->sum('marks')]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Question added successfully.',
                'question' => $question->load('options'),
            ]);
        }

        return redirect()->route('homework.show', $homework)
            ->with('success', 'Question added successfully.');
    }

    public function update(Request $request, HomeworkQuestion $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:true_false,multiple_choice,essay',
            'marks' => 'required|integer|min:1',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*' => 'required_if:question_type,multiple_choice|string',
            'correct_option' => 'required_if:question_type,multiple_choice|integer',
            'correct_answer' => 'required_if:question_type,true_false|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $questionData = [
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'marks' => $request->marks,
        ];

        if ($request->hasFile('image')) {
            $questionData['image'] = $request->file('image');
        }

        $question->update($questionData);

        // Delete existing options
        $question->options()->delete();

        // Create new options based on question type
        if ($request->question_type === 'multiple_choice') {
            foreach ($request->options as $index => $optionText) {
                $question->options()->create([
                    'option_text' => $optionText,
                    'is_correct' => ($index == $request->correct_option),
                    'order' => $index + 1,
                ]);
            }
        } elseif ($request->question_type === 'true_false') {
            $question->options()->create([
                'option_text' => 'True',
                'is_correct' => $request->correct_answer == 1,
                'order' => 1,
            ]);
            $question->options()->create([
                'option_text' => 'False',
                'is_correct' => $request->correct_answer == 0,
                'order' => 2,
            ]);
        }

        // Update homework total marks
        $question->homework->update(['total_marks' => $question->homework->questions()->sum('marks')]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Question updated successfully.',
                'question' => $question->load('options'),
            ]);
        }

        return redirect()->route('homework.show', $question->homework)
            ->with('success', 'Question updated successfully.');
    }

    public function destroy(HomeworkQuestion $question)
    {
        $homework = $question->homework;
        $question->delete();

        // Update homework total marks
        $homework->update(['total_marks' => $homework->questions()->sum('marks')]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Question deleted successfully.',
            ]);
        }

        return redirect()->route('homework.show', $homework)
            ->with('success', 'Question deleted successfully.');
    }
}
