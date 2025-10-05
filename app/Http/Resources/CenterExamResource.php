<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CenterExamResource extends JsonResource
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
            'passing_marks' => $this->passing_marks,
            'duration_minutes' => $this->duration_minutes,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'is_active' => $this->is_active,
            'center' => new CentersResource($this->center),
            'stage' => new StagesResource($this->stage),
            'grade' => new GradesResource($this->grade),
            'division' => new DivisionResource($this->division),
            'questions' => CenterExamQuestionResource::collection($this->questions),
            'is_answered' => auth('student')->user() ? $this->attempts()->where('student_id', auth('student')->id())->exists() : null,

        ];
    }
}
