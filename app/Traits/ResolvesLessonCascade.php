<?php

namespace App\Traits;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

trait ResolvesLessonCascade
{
    /**
     * Resolve cascade prefill values for Stage → Grade → Course → Chapter → Lesson selects.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lesson|null  $lesson
     * @param  int|string|null  $fallbackLessonId
     * @return array<string, int|null>
     */
    protected function resolveLessonCascadePrefill(
        Request $request,
        ?Lesson $lesson = null,
        $fallbackLessonId = null
    ): array {
        $prefill = [
            'stage_id' => null,
            'grade_id' => null,
            'course_id' => null,
            'chapter_id' => null,
            'lesson_id' => null,
        ];

        $lessonId = $this->coalesceId($request->query('lesson_id'), $fallbackLessonId);
        $chapterId = $this->coalesceId($request->query('chapter_id'));
        $courseId = $this->coalesceId($request->query('course_id'));
        $gradeId = $this->coalesceId($request->query('grade_id'));
        $stageId = $this->coalesceId($request->query('stage_id'));

        $resolvedLesson = null;

        if ($lessonId) {
            $resolvedLesson = Lesson::with('chapter.course.stage', 'chapter.course.grade')
                ->find($lessonId);
        } elseif ($lesson) {
            $lesson->loadMissing('chapter.course.stage', 'chapter.course.grade');
            $resolvedLesson = $lesson;
        }

        if ($resolvedLesson) {
            $course = optional($resolvedLesson->chapter)->course;
            $prefill['lesson_id'] = $resolvedLesson->id;
            $prefill['chapter_id'] = optional($resolvedLesson->chapter)->id;
            $prefill['course_id'] = optional($course)->id;
            $prefill['grade_id'] = optional($course)->grade_id;
            $prefill['stage_id'] = optional($course)->stage_id;
        } elseif ($chapterId) {
            $chapter = Chapter::with('course.stage', 'course.grade')->find($chapterId);
            if ($chapter) {
                $course = $chapter->course;
                $prefill['chapter_id'] = $chapter->id;
                $prefill['course_id'] = optional($course)->id;
                $prefill['grade_id'] = optional($course)->grade_id;
                $prefill['stage_id'] = optional($course)->stage_id;
            }
        } elseif ($courseId) {
            $course = Course::with('stage', 'grade')->find($courseId);
            if ($course) {
                $prefill['course_id'] = $course->id;
                $prefill['grade_id'] = $course->grade_id;
                $prefill['stage_id'] = $course->stage_id;
            }
        }

        // Allow explicit query overrides when present.
        $prefill['stage_id'] = $stageId ?? $prefill['stage_id'];
        $prefill['grade_id'] = $gradeId ?? $prefill['grade_id'];
        $prefill['course_id'] = $courseId ?? $prefill['course_id'];
        $prefill['chapter_id'] = $chapterId ?? $prefill['chapter_id'];
        $prefill['lesson_id'] = $lessonId ?? $prefill['lesson_id'];

        foreach ($prefill as $key => $value) {
            $prefill[$key] = $value !== null ? (int) $value : null;
        }

        return $prefill;
    }

    /**
     * Determine the first non-empty identifier value.
     *
     * @param  mixed  ...$candidates
     */
    private function coalesceId(...$candidates): ?int
    {
        foreach ($candidates as $candidate) {
            if ($candidate === null || $candidate === '') {
                continue;
            }

            if (is_numeric($candidate)) {
                return (int) $candidate;
            }
        }

        return null;
    }
}
