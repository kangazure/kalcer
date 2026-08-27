<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Testimonial */
class TestimonialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'customer_name' => $this->customer_name,
            'customer_role' => $this->customer_role,
            'customer_photo_url' => $this->customer_photo_url,
            'rating' => $this->rating,
            'content' => $this->content,
            'package' => $this->whenLoaded('package', fn () => $this->package ? ['id' => $this->package->id, 'name' => $this->package->name] : null),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
