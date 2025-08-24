<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'bio' => $this->bio,
            'image' => $this->image,
            'stages' => $this->stages->unique('id')->pluck('name')->values(),
            'grades' => $this->grades->unique('id')->pluck('name')->values(),
            'divisions' => $this->divisions->unique('id')->pluck('name')->values(),
            'subjects' => SubjectResource::collection($this->subjects->unique('id')),
            'courses' => CourseResource::collection($this->courses->unique('id')),
            'lessons' => LessonResource::collection($this->lessons()->get()->unique('id')),
        ];
    }
}
