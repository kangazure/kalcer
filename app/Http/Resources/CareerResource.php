<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Career */
class CareerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'department' => $this->department,
            'location' => $this->location,
            'job_type' => $this->job_type,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'responsibilities' => $this->responsibilities,
            'benefits' => $this->benefits,
            'salary' => [
                'min' => $this->salary_min ? (float) $this->salary_min : null,
                'max' => $this->salary_max ? (float) $this->salary_max : null,
                'is_negotiable' => $this->salary_is_negotiable,
            ],
            'vacancy_count' => $this->vacancy_count,
            'closes_at' => $this->closes_at?->toIso8601String(),
        ];
    }
}
