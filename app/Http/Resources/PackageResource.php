<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Package */
class PackageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->category,
            'speed' => ['download_mbps' => $this->speed_mbps_download, 'upload_mbps' => $this->speed_mbps_upload],
            'price' => [
                'normal' => (float) $this->price,
                'promo' => $this->price_promo ? (float) $this->price_promo : null,
                'effective' => $this->effective_price,
                'has_promo' => $this->has_promo,
                'billing_cycle' => $this->billing_cycle,
                'installation_fee' => (float) $this->installation_fee,
            ],
            'is_unlimited' => $this->is_unlimited,
            'fup_gb' => $this->fup_gb,
            'features' => $this->features,
            'is_popular' => $this->is_popular,
            'service' => $this->whenLoaded('service', fn () => [
                'id' => $this->service->id, 'name' => $this->service->name, 'slug' => $this->service->slug,
            ]),
        ];
    }
}
