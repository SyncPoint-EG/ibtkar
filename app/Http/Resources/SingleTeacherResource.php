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
        $student = auth('student')->user();
        $coursesQuery = $this->courses();
        if ($student) {
            $coursesQuery->where('stage_id', $student->stage_id)
                ->where('grade_id', $student->grade_id);

//            if ($student->division_id) {
//                $coursesQuery->where(function ($query) use ($student) {
//                    $query->where('division_id', $student->division_id)
//                        ->orWhereNull('division_id');
//                });
//            }
        }
        $courses = $coursesQuery->get()->unique('id');
        $courseIds = $courses->pluck('id');
        $chapters = \App\Models\Chapter::whereIn('course_id', $courseIds)->get()->unique('id');
        $lessons = \App\Models\Lesson::whereIn('chapter_id', $chapters->pluck('id'))->with('attachments')->latest()->get()->unique('id');
//        $attachments = LessonAttachment::query()->whereHas('lesson.chapter.course', function ($query) {
//            $query->whereIn('teacher_id', [$this->id]);
//
//        })->take(5)->get();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'bio' => $this->bio,
            'image' => $this->image,
            'stages' => $this->stages->pluck('name'),
            'grades' => $this->grades->pluck('name'),
            'divisions' => $this->divisions->pluck('name'),
            'subjects' => SubjectResource::collection($this->subjects),
//            'courses' => CourseResource::collection($this->courses),
//            'lessons' => LessonResource::collection($this->lessons()->latest()->get()),

            'courses' => CourseResource::collection($courses),
            'chapters' => ChapterResource::collection($chapters),
            'lessons' => LessonResource::collection($lessons),

            'exams'   => Exam::whereHas('lesson.chapter.course', function ($query) {
                $query->whereIn('teacher_id', [$this->id]);
            })->where('start_date','>',now())->take(5)->get(),
            'attachments' => LessonAttachmentResource::collection($lessons->attachments()->take(5)->get()),
            'stories'  => StoryResource::collection($this->stories()->where('created_at', '>=', now()->subDay())->get()),


        ];
    }
}
