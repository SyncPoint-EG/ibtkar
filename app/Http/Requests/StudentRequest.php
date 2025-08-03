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
        return [

            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'gender' => ['required','in:Male,Female'],
            'birth_date' => ['nullable', 'date'],
            'stage_id' => ['required', 'exists:stages,id'],
            'grade_id' => ['required', 'exists:grades,id'],
            'division_id' => ['required', 'exists:divisions,id'],
            'center_id' => ['required', 'exists:centers,id'],
            'governorate_id' => ['required', 'exists:governorates,id'],
            'district_id' => ['nullable', 'exists:districts,id'],
        ];
    }
}
