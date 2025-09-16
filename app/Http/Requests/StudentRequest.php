<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
        $studentId = $this->route('student') ? $this->route('student')->id : null;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'unique:students,phone,' . $studentId],
            'password' => [$studentId ? 'nullable' : 'required', 'string', 'min:8'],
            'gender' => ['nullable', 'in:Male,Female'],
            'birth_date' => ['nullable', 'date'],
            'stage_id' => ['required', 'exists:stages,id'],
            'grade_id' => ['required', 'exists:grades,id'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'education_type_id' => ['required', 'exists:education_types,id'],
            'center_id' => ['required', 'exists:centers,id'],
            'governorate_id' => ['required', 'exists:governorates,id'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'referral_code' => 'nullable|exists:students,referral_code',
            'guardian_number' => ['nullable', 'string', 'max:255'],
            'mac_address' => ['nullable', 'string', 'max:255'],
        ];
    }
}
