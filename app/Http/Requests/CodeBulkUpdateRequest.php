<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CodeBulkUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code_classification' => 'required|string',
            'for' => 'nullable|string|in:course,chapter,lesson,charge',
            'teacher_id' => 'nullable|exists:teachers,id',
            'expires_at' => 'nullable|date',
            'price' => 'nullable|numeric',
        ];
    }
}
