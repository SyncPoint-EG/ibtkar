<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name ,
            'phone'      => $this->phone ,
            'gender'      => $this->gender ,
            'governorate'  => $this->governorate?->name,
            'district'     => $this->district?->name ,
            'stage'        => $this->stage?->name,
            'grade'        => $this->grade?->name,
            'division'     => $this->division?->name,
            'center'       => $this->center?->name ,
            'education_type'       => $this->center?->name ,
            'status'       => $this->status ,
            'points'       => $this->points,
            'referral_code'       => $this->referral_code,
        ];
    }
}
