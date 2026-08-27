<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Post */
class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->when($request->routeIs('*.show'), $this->content_html),
            'cover_image_url' => $this->cover_image_url,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'is_pinned' => $this->is_pinned,
            'reading_time_minutes' => $this->reading_time_minutes,
            'stats' => [
                'view_count' => $this->view_count,
                'like_count' => $this->like_count,
                'comment_count' => $this->comment_count,
                'bookmark_count' => $this->bookmark_count,
            ],
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id, 'name' => $this->category->name, 'slug' => $this->category->slug,
            ]),
            'author' => $this->whenLoaded('author', fn () => [
                'id' => $this->author->id, 'name' => $this->author->name, 'avatar_url' => $this->author->avatar_url,
            ]),
            'tags' => $this->whenLoaded('tags', fn () => $this->tags->map(fn ($tag) => [
                'id' => $tag->id, 'name' => $tag->name, 'slug' => $tag->slug,
            ])),
            'seo' => [
                'title' => $this->seo_title,
                'description' => $this->seo_description,
                'og_image' => $this->og_image_url,
                'canonical_url' => $this->canonical_url,
            ],
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
