<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Portfolio extends BaseModel
{
    use HasSlug;

    protected $table = 'portfolio';

    protected $fillable = [
        'title', 'slug', 'client_name', 'category', 'location', 'summary',
        'description', 'cover_image_url', 'result_metric_label',
        'result_metric_value', 'project_year', 'is_featured', 'is_published',
        'sort_order', 'seo_title', 'seo_description', 'created_by',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'project_year' => 'integer',
        'sort_order' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
