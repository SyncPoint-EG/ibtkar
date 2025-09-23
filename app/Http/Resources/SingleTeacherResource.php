<?php

namespace App\Http\Resources;

use App\Models\Exam;
use App\Models\LessonAttachment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\StoryResource;

class SingleTeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attachments = LessonAttachment::query()->whereHas('lesson.chapter.course', function ($query) {
            $query->whereIn('teacher_id', [$this->id]);

        })->take(5)->get();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'bio' => $this->bio,
            'image' => $this->image,
            'stages' => $this->stages->pluck('name'),
            'grades' => $this->grades->pluck('name'),
            'divisions' => $this->divisions->pluck('name'),
            'subjects' => SubjectResource::collection($this->subjects),
            'courses' => CourseResource::collection($this->courses),
            'lessons' => LessonResource::collection($this->lessons()->latest()->get()),
            'exams'   => Exam::whereHas('lesson.chapter.course', function ($query) {
                $query->whereIn('teacher_id', [$this->id]);
            })->where('start_date','>',now())->take(5)->get(),
            'attachments' => LessonAttachmentResource::collection($this->attachments()->take(5)->get()),
            'stories'  => StoryResource::collection($this->stories()->where('created_at', '>=', now()->subDay())->get()),


        ];
    }
}
