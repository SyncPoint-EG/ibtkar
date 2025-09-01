<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HomeworkResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'total_marks' => $this->total_marks,
            'is_active' => $this->is_active,
            'due_date' => $this->due_date,
            'lesson_id' => $this->lesson_id,
            'questions' => HomeworkQuestionResource::collection($this->questions),
            'is_answered' => auth('student')->user() ?  $this->attempts()->where('student_id', auth('student')->id())->exists() : null,
        ];
    }
}
