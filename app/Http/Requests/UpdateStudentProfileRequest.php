<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentProfileRequest extends FormRequest
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
        $studentId = $this->user()->id;

        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'string', 'max:255', Rule::unique('students')->ignore($studentId)],
            'password' => ['sometimes', 'string', 'min:8'],
            'gender' => ['sometimes', 'in:Male,Female'],
            'birth_date' => ['nullable', 'date'],
            'stage_id' => ['sometimes', 'exists:stages,id'],
            'grade_id' => ['sometimes', 'exists:grades,id'],
            'division_id' => ['sometimes', 'exists:divisions,id'],
            'education_type_id' => ['sometimes', 'exists:education_types,id'],
            'center_id' => ['sometimes', 'exists:centers,id'],
            'governorate_id' => ['sometimes', 'exists:governorates,id'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'guardian_number' => ['nullable', 'string', 'max:255'],
        ];
    }
}
