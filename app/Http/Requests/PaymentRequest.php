<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
        return [
            'payment_method'    => 'required|in:instapay,wallet,ibtkar_wallet,code',
            'payment_image'     => 'required_if:payment_method,instapay,wallet|image|mimes:jpg,jpeg,png',
            'phone_number'      => 'required_if:payment_method,instapay,ibtkar_wallet|nullable',
            'payment_code'      => 'required_if:payment_method,code|nullable',
//            'course_id'         => 'required_without:lesson_id,chapter_id|nullable|exists:courses,id',
//            'chapter_id'        => 'required_without:lesson_id,chapter_id|nullable|exists:chapters,id',
//            'lesson_id'         => 'required_without:course_id,chapter_id|nullable|exists:lessons,id',
            'course_id'         => 'nullable|exists:courses,id',
            'chapter_id'        => 'nullable|exists:chapters,id',
            'lesson_id'         => 'nullable|exists:lessons,id',
        ];
    }
}
