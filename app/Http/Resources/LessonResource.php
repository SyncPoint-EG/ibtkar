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
        $watches_count = null ;
        if($user){
            $watches_count  = $this->resource->watches()->where('student_id', $user->id)?->first() ? 3 -  $this->resource->watches()->where('student_id', $user->id)?->first()?->count : 3 ;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'grade_id' => $this->chapter?->course?->grade_id,
            'desc' => $this->desc,
            'video_link' => $this->video_link,
            'video_image' => $this->video_image,
            'is_featured' => $this->is_featured,
            'type' => $this->type,
            'chapter_id' => $this->chapter_id,
            'chapter_name' => $this->chapter?->name,
            'price' => $this->price,
            'course_id' => $this->chapter ? $this->chapter?->course_id : null,
            'subject' => new SubjectResource($this?->chapter?->course?->subject),
            'attachments' => AttachmentResource::collection($this->attachments ?? collect()),
            'homework' => HomeworkResource::collection($this->homework ?? collect()),
            'exams' => ExamResource::collection($this->exams ?? collect()),
            'watches_count' => $watches_count,
            'is_purchased' => $user ? $user->isLessonPurchased($this->id) : false,
            'date' => $this->date,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'is_passed_exam' => $this->exams->isEmpty() ? true : ($user ? $user->examAttempts()->whereIn('exam_id', $this->exams->pluck('id'))->where('is_passed', true)->exists() : false),

        ];
    }
}
