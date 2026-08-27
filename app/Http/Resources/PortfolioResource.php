<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Portfolio */
class PortfolioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'client_name' => $this->client_name,
            'category' => $this->category,
            'location' => $this->location,
            'summary' => $this->summary,
            'description' => $this->when($request->routeIs('*.show'), $this->description),
            'cover_image_url' => $this->cover_image_url,
            'result' => ['label' => $this->result_metric_label, 'value' => $this->result_metric_value],
            'project_year' => $this->project_year,
            'is_featured' => $this->is_featured,
        ];
    }
}
