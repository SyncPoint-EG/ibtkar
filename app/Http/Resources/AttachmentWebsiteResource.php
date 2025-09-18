<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentWebsiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'name'   => $this->name,
            'bio'    => $this->bio,
            'grade'  => $this?->lesson?->chapter?->course?->grade?->name,
            'subject'  => $this?->lesson?->chapter?->course?->subject?->name,
            'teacher'  => $this?->lesson?->chapter?->course?->teacher?->name,
            'teacher_image'  => $this?->lesson?->chapter?->course?->teacher?->image,
//            'path' => $this->path,
//            'type' => $this->type,
//            'file' => $this->file,
        ];
    }
}
