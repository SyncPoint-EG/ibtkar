<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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

            'name' => 'required',
            'year' => 'required',
            'teacher_id' => 'required|exists:teachers,id',
            'education_type_id' => 'required|exists:education_types,id',
            'stage_id' => 'required|exists:stages,id',
            'grade_id' => 'required|exists:grades,id',
            'division_id' => 'nullable|exists:divisions,id',
            'semister_id' => 'required|exists:semisters,id',
            'subject_id' => 'required|exists:subjects,id',
            'price' => 'nullable|numeric',
            'is_featured' => 'nullable|boolean',
            'bio' => 'nullable|string',
            'website_image' => 'nullable|image',
        ];
    }
}
