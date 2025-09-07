<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'exam_for' => 'required|in:lesson,teacher',
            'pass_degree' => 'required|integer|min:0',
        ];

        if ($this->input('exam_for') === 'lesson') {
            $rules['lesson_id'] = 'required|exists:lessons,id';
        } else {
            $rules['teacher_id'] = 'required|exists:teachers,id';
            $rules['course_id'] = 'required|exists:courses,id';
        }

        return $rules;
    }
}
