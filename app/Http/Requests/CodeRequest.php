<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CodeRequest extends FormRequest
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
        return [
            'code' => 'nullable|string',
            'for' => 'required|string|in:course,chapter,lesson,charge,grade_plan',
            'teacher_id' => 'required_unless:for,grade_plan|nullable|exists:teachers,id',
            'expires_at' => 'nullable|date',
            'code_classification' => 'nullable|string',
            'price' => 'nullable|numeric',
        ];
    }
}
