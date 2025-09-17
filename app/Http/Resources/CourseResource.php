<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'price' => $this->price,
            'website_image' => $this->website_image,
            'bio' => $this->bio,
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'subject' => new SubjectResource($this->whenLoaded('subject')),
            'chapters' => ChapterResource::collection($this->chapters),
        ];
    }
}
