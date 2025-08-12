<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
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
            'desc' => 'required',
            'chapter_id' => 'required|exists:chapters,id',
            'video_link' => 'required',
            'video_image' => 'required',
            'price' => 'nullable|numeric',
            'is_featured' => 'nullable|boolean',
        ];
    }
}
