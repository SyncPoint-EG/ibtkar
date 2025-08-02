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
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'other_phone'  => 'nullable|string|max:20',
            'bio'          => 'nullable|string',
            'image'        => 'nullable|image',
            'rate'         => 'nullable|numeric',

            // Validate assignments (new way)
            'assignments' => 'required|array|min:1',
            'assignments.*.subject_id'  => 'required|exists:subjects,id',
            'assignments.*.stage_id'    => 'required|exists:stages,id',
            'assignments.*.grade_id'    => 'required|exists:grades,id',
            'assignments.*.division_id' => 'nullable|exists:divisions,id',
        ];
    }
}
