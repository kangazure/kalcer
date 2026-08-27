<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use League\CommonMark\CommonMarkConverter;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends BaseModel
{
    use HasFactory, HasSlug, SoftDeletes;

    protected $table = 'posts';

    protected $fillable = [
        'category_id', 'author_id', 'title', 'slug', 'excerpt', 'content',
        'content_html', 'cover_image_url', 'status', 'is_featured', 'is_pinned',
        'seo_title', 'seo_description', 'og_image_url', 'canonical_url',
        'published_at', 'scheduled_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_pinned' => 'boolean',
        'view_count' => 'integer',
        'like_count' => 'integer',
        'comment_count' => 'integer',
        'bookmark_count' => 'integer',
        'share_count' => 'integer',
        'reading_time_minutes' => 'integer',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    protected static function booted(): void
    {
        // Render markdown -> HTML setiap kali content disimpan, supaya
        // frontend cukup menampilkan content_html tanpa parsing ulang.
        static::saving(function (Post $post) {
            if ($post->isDirty('content')) {
                $converter = new CommonMarkConverter([
                    'html_input' => 'strip',
                    'allow_unsafe_links' => false,
                ]);
                $post->content_html = (string) $converter->convert($post->content ?? '');
            }
        });
    }

    // ------------------------------------------------------------------
    // Relationships
    // ------------------------------------------------------------------
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function approvedComments(): HasMany
    {
        return $this->comments()->where('status', 'approved')->whereNull('parent_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class, 'post_id');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(PostBookmark::class, 'post_id');
    }

    // ------------------------------------------------------------------
    // Scopes
    // ------------------------------------------------------------------
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNull('deleted_at')
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSearch($query, ?string $keyword)
    {
        if (blank($keyword)) {
            return $query;
        }

        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'ilike', "%{$keyword}%")
                ->orWhere('excerpt', 'ilike', "%{$keyword}%")
                ->orWhere('content', 'ilike', "%{$keyword}%");
        });
    }

    // ------------------------------------------------------------------
    // Accessors
    // ------------------------------------------------------------------
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getReadableUrlAttribute(): string
    {
        return route('blog.show', $this->slug);
    }
}
