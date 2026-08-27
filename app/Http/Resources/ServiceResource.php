<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Service */
class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'short_description' => $this->short_description,
            'description' => $this->when($request->routeIs('*.show'), $this->description),
            'features' => $this->features,
            'benefits' => $this->benefits,
            'cover_image_url' => $this->cover_image_url,
            'icon_image_url' => $this->icon_image_url,
            'packages' => PackageResource::collection($this->whenLoaded('packages')),
        ];
    }
}
