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
        $user = auth('student')->user();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'desc' => $this->desc,
            'video_link' => $this->video_link,
            'video_image' => $this->video_image,
            'is_featured' => $this->is_featured,
            'type' => $this->type,
            'chapter_id' => $this->chapter_id,
            'price' => $this->price,
            'course_id' => $this->chapter ? $this->chapter->course_id : null,
            'subject' => new SubjectResource($this->chapter->course->subject),
            'attachments' => $this->attachments,
            'homework' => $this->homework,
            'exams' => $this->exams,
            'watches' => $this->watches()->where('student_id', $user->id)->first() ? $this->watches()->where('student_id', $user->id)->first()->count : 3,

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),

        ];
    }
}
