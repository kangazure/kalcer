<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Service extends BaseModel
{
    use HasSlug;

    protected $table = 'services';

    protected $fillable = [
        'name', 'slug', 'icon', 'short_description', 'description',
        'features', 'benefits', 'cover_image_url', 'icon_image_url',
        'sort_order', 'is_active', 'is_featured_home', 'seo_title', 'seo_description',
    ];

    protected $casts = [
        'features' => 'array',
        'benefits' => 'array',
        'is_active' => 'boolean',
        'is_featured_home' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class, 'service_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeFeaturedHome($query)
    {
        return $query->where('is_featured_home', true);
    }
}
