<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DashboardNotificationRequest extends FormRequest
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
            'recipient_type' => ['required', 'in:students,guardians,both'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'stage_ids' => ['nullable', 'array'],
            'stage_ids.*' => ['integer', 'exists:stages,id'],
            'grade_ids' => ['nullable', 'array'],
            'grade_ids.*' => ['integer', 'exists:grades,id'],
            'division_ids' => ['nullable', 'array'],
            'division_ids.*' => ['integer', 'exists:divisions,id'],
        ];
    }
}
