<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherStudentPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $watch = $this->student->watches()->where('lesson_id', $this->lesson_id)->first();
        return [
            'student_id' => $this->student_id,
            'student_name' => $this->student->name,
            'lesson_id' => $this->lesson_id,
            'lesson_name' => $this->lesson->name,
            'is_watched' => (bool)$watch,
            'watch_count' => $watch ? $watch->count : 0,
        ];
    }
}
