<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
            'exam_type' => 'required|in:lesson,teacher',
        ];

        if ($this->input('exam_type') === 'lesson') {
            $rules['lesson_id'] = 'required|exists:lessons,id';
        } else {
            $rules['course_id'] = 'required|exists:courses,id';
            // teacher_id is optional on the exam itself, as it can be inferred from the course.
            $rules['teacher_id'] = 'nullable|exists:teachers,id';
        }

        return $rules;
    }
}