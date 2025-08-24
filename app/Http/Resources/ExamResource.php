<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
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
            'duration_minutes' => $this->duration_minutes,
            'total_marks' => $this->total_marks,
            'is_active' => $this->is_active,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'lesson_id' => $this->lesson_id,
            'questions' => QuestionResource::collection($this->questions),
            'subject' => new SubjectResource($this->lesson->chapter->course->subject),
        ];
    }
}
