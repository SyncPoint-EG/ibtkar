<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'data' => ['sometimes', 'array'],
            'data.*' => ['nullable'],
            'recipient_type' => ['sometimes', 'in:students,guardians,both'],
            'send_to_auth' => ['sometimes', 'boolean'],
            'student_ids' => ['sometimes', 'array'],
            'student_ids.*' => ['integer', 'exists:students,id'],
            'guardian_ids' => ['sometimes', 'array'],
            'guardian_ids.*' => ['integer', 'exists:guardians,id'],
            'teacher_ids' => ['sometimes', 'array'],
            'teacher_ids.*' => ['integer', 'exists:teachers,id'],
            'stage_ids' => ['sometimes', 'array'],
            'stage_ids.*' => ['integer', 'exists:stages,id'],
            'grade_ids' => ['sometimes', 'array'],
            'grade_ids.*' => ['integer', 'exists:grades,id'],
            'division_ids' => ['sometimes', 'array'],
            'division_ids.*' => ['integer', 'exists:divisions,id'],
        ];
    }
}
