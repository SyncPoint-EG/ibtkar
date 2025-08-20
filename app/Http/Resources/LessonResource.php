<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
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
            'desc' => $this->desc,
            'video_link' => $this->video_link,
            'video_image' => $this->video_image,
            'is_featured' => $this->is_featured,
            'type' => $this->type,
            'chapter_id' => $this->chapter_id,
            'course_id' => $this->chapter ? $this->chapter->course_id : null,
            'subject' => new SubjectResource($this->chapter->course->subject),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),

        ];
    }
}
