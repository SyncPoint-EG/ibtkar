<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CenterExam;
use App\Models\CenterExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CenterExamQuestionController extends Controller
{
    public function store(Request $request, CenterExam $centerExam)
    {
        $validatedData = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:true_false,multiple_choice,essay',
            'marks' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'correct_essay_answer' => 'nullable|string',
            'correct_answer' => 'required_if:question_type,true_false|boolean',
            'options' => 'required_if:question_type,multiple_choice|array',
            'options.*' => 'required_if:question_type,multiple_choice|string',
            'correct_option' => 'required_if:question_type,multiple_choice|integer',
        ]);

        DB::beginTransaction();
        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('center_exam_questions', 'public');
            }

            $question = $centerExam->questions()->create([
                'question_text' => $validatedData['question_text'],
                'question_type' => $validatedData['question_type'],
                'marks' => $validatedData['marks'],
                'image' => $imagePath,
                'correct_essay_answer' => $validatedData['question_type'] === 'essay' ? $validatedData['correct_essay_answer'] : null,
            ]);

            if ($validatedData['question_type'] === 'true_false') {
                $question->options()->createMany([
                    ['option_text' => 'True', 'is_correct' => $validatedData['correct_answer'] == 1],
                    ['option_text' => 'False', 'is_correct' => $validatedData['correct_answer'] == 0],
                ]);
            } elseif ($validatedData['question_type'] === 'multiple_choice') {
                foreach ($validatedData['options'] as $index => $optionText) {
                    $question->options()->create([
                        'option_text' => $optionText,
                        'is_correct' => $index == $validatedData['correct_option'],
                    ]);
                }
            }

            DB::commit();

            $question->load('options');

            return response()->json([
                'success' => true,
                'message' => 'Question added successfully.',
                'question' => $question,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to add question.',
                'errors' => ['general' => [$e->getMessage()]],
            ], 500);
        }
    }

    public function update(Request $request, CenterExamQuestion $question)
    {
        $validatedData = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:true_false,multiple_choice,essay',
            'marks' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'correct_essay_answer' => 'nullable|string',
            'correct_answer' => 'required_if:question_type,true_false|boolean',
            'options' => 'required_if:question_type,multiple_choice|array',
            'options.*' => 'required_if:question_type,multiple_choice|string',
            'correct_option' => 'required_if:question_type,multiple_choice|integer',
        ]);

        DB::beginTransaction();
        try {
            $imagePath = $question->image;
            if ($request->hasFile('image')) {
                if ($imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image')->store('center_exam_questions', 'public');
            }

            $question->update([
                'question_text' => $validatedData['question_text'],
                'question_type' => $validatedData['question_type'],
                'marks' => $validatedData['marks'],
                'image' => $imagePath,
                'correct_essay_answer' => $validatedData['question_type'] === 'essay' ? $validatedData['correct_essay_answer'] : null,
            ]);

            $question->options()->delete(); // Remove old options

            if ($validatedData['question_type'] === 'true_false') {
                $question->options()->createMany([
                    ['option_text' => 'True', 'is_correct' => $validatedData['correct_answer'] == 1],
                    ['option_text' => 'False', 'is_correct' => $validatedData['correct_answer'] == 0],
                ]);
            } elseif ($validatedData['question_type'] === 'multiple_choice') {
                foreach ($validatedData['options'] as $index => $optionText) {
                    $question->options()->create([
                        'option_text' => $optionText,
                        'is_correct' => $index == $validatedData['correct_option'],
                    ]);
                }
            }

            DB::commit();

            $question->load('options');

            return response()->json([
                'success' => true,
                'message' => 'Question updated successfully.',
                'question' => $question,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update question.',
                'errors' => ['general' => [$e->getMessage()]],
            ], 500);
        }
    }

    public function destroy(CenterExamQuestion $question)
    {
        DB::beginTransaction();
        try {
            if ($question->image) {
                Storage::disk('public')->delete($question->image);
            }
            $question->options()->delete();
            $question->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Question deleted successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete question.',
                'errors' => ['general' => [$e->getMessage()]],
            ], 500);
        }
    }

    public function edit(CenterExamQuestion $question)
    {
        $question->load('options');

        return response()->json([
            'success' => true,
            'question' => $question,
        ]);
    }
}
