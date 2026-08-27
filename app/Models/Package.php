<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Package extends BaseModel
{
    use HasSlug;

    protected $table = 'packages';

    protected $fillable = [
        'service_id', 'category', 'name', 'slug', 'speed_mbps_download',
        'speed_mbps_upload', 'price', 'price_promo', 'billing_cycle',
        'is_unlimited', 'fup_gb', 'installation_fee', 'features',
        'is_popular', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'speed_mbps_download' => 'integer',
        'speed_mbps_upload' => 'integer',
        'price' => 'decimal:2',
        'price_promo' => 'decimal:2',
        'is_unlimited' => 'boolean',
        'installation_fee' => 'decimal:2',
        'features' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->price_promo ?? $this->price);
    }

    public function getHasPromoAttribute(): bool
    {
        return ! is_null($this->price_promo) && $this->price_promo < $this->price;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
