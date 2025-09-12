<?php

namespace App\Http\Resources;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectTeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->teacher->id,
            'name' => $this->teacher->name,
            'bio' => $this->teacher->bio,
            'image' => $this->teacher->image,
            'day_of_week' => Teacher::DAYS_OF_WEEK[$this->day_of_week] ?? null,
            'hour'        => $this->time

        ];
    }
}