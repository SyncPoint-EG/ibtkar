<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GradePlanRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $gradePlanId = $this->route('grade_plan')?->id ?? $this->route('gradePlan')?->id;

        return [
            'stage_id' => ['required', 'exists:stages,id'],
            'grade_id' => [
                'required',
                'exists:grades,id',
                Rule::unique('grade_plans')->where(function ($query) {
                    return $query->where('stage_id', $this->input('stage_id'));
                })->ignore($gradePlanId),
            ],
            'general_plan_price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
