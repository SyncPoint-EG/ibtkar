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
            'stages' => $this?->courses->stages->pluck('name'),
            'grades' => $this?->courses->grades->pluck('name'),
            'divisions' => $this?->courses->divisions->pluck('name'),
            'subjects' => SubjectResource::collection($this?->courses->subjects),
            'courses' => CourseResource::collection($this->courses),
        ];
    }
}
