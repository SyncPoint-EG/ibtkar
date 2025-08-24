<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HomeworkQuestionResource extends JsonResource
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
            'is_required' => $this->is_required,
            'image' => $this->image,
            'options' => HomeworkQuestionOptionResource::collection($this->options),
        ];
    }
}
