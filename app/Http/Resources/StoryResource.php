<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $stories = $this->resource;
        $teacher = $stories->first()->teacher;

        return [
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'image' => $teacher->image,
            ],
            'stories' => $stories->map(function ($story) {
                return [
                    'id' => $story->id,
                    'file' => $story->file,
                    'type' => $story->type,
                    'created_at' => $story->created_at->toDateTimeString(),
                ];
            }),
        ];
    }
}
