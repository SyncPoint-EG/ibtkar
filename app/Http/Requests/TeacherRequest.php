<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'other_phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'image' => 'nullable|image',
            'rate' => 'nullable|numeric',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'stages' => 'nullable|array',
            'stages.*' => 'exists:stages,id',
            'grades' => 'nullable|array',
            'grades.*' => 'exists:grades,id',
            'divisions' => 'nullable|array',
            'divisions.*' => 'exists:divisions,id',
        ];
    }
}
