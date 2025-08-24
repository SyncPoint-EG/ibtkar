<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'question_text' => $this->question_text,
            'question_type' => $this->question_type,
            'marks' => $this->marks,
            'order' => $this->order,
            'image' => $this->image,
            'correct_essay_answer' => $this->correct_essay_answer,
            'options' => QuestionOptionResource::collection($this->options),
        ];
    }
}
