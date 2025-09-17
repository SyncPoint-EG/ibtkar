<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherWebsiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $student = auth('student')->user();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'bio' => $this->bio,
            'image' => $this->image,
            'website_image' => $this->website_image,
            'stages' => $this->stages->unique('id')->pluck('name')->values(),
            'grades' => $this->grades->unique('id')->pluck('name')->values(),
            'divisions' => $this->divisions->unique('id')->pluck('name')->values(),

        ];
    }
}
