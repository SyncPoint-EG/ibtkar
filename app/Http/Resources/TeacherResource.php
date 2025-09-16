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
        $lessons = \App\Models\Lesson::whereIn('chapter_id', $chapters->pluck('id'))->with('attachments')->get()->unique('id');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'bio' => $this->bio,
            'image' => $this->image,
            'website_image' => $this->website_image,
            'stages' => $this->stages->unique('id')->pluck('name')->values(),
            'grades' => $this->grades->unique('id')->pluck('name')->values(),
            'divisions' => $this->divisions->unique('id')->pluck('name')->values(),
            'subjects' => SubjectResource::collection($this->subjects->unique('id')),
            'courses' => CourseResource::collection($courses),
            'chapters' => ChapterResource::collection($chapters),
            'lessons' => LessonResource::collection($lessons),
        ];
    }
}
