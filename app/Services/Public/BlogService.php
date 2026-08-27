<?php

namespace App\Services\Public;

use App\Events\PostPublished;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostBookmark;
use App\Models\PostLike;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Business logic untuk blog publik: listing, detail, like/bookmark,
 * komentar, dan generate slug unik. Dipisahkan dari Controller/Repository
 * karena melibatkan beberapa langkah non-trivial (mis. toggle like harus
 * update counter denormalized di tabel posts).
 */
class BlogService
{
    public function listPublished(array $filters = [], ?string $keyword = null, int $perPage = 9): LengthAwarePaginator
    {
        $query = Post::query()
            ->with(['category:id,name,slug', 'author:id,name,avatar_url'])
            ->where('status', 'published')
            ->whereNull('deleted_at');

        if (! empty($filters['category_slug'])) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $filters['category_slug']));
        }

        if (! empty($filters['tag_slug'])) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $filters['tag_slug']));
        }

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'ilike', "%{$keyword}%")
                    ->orWhere('excerpt', 'ilike', "%{$keyword}%");
            });
        }

        return $query->orderByDesc('is_pinned')->orderByDesc('published_at')->paginate($perPage);
    }

    public function showBySlug(string $slug): ?Post
    {
        $post = Post::query()
            ->with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if ($post) {
            $post->increment('view_count');
        }

        return $post;
    }

    public function sidebarData(int $postId): array
    {
        return [
            'related' => Post::query()
                ->where('id', '!=', $postId)
                ->where('status', 'published')
                ->latest('published_at')
                ->limit(4)
                ->get(['id', 'title', 'slug', 'cover_image_url', 'published_at']),
        ];
    }

    public function recentForHome(int $limit = 6): Collection
    {
        return Post::query()
            ->with('category:id,name')
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function featuredForHome(int $limit = 5): Collection
    {
        return Post::query()
            ->where('status', 'published')
            ->where('is_featured', true)
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function toggleLike(int $postId, ?int $userId, ?string $ip = null): bool
    {
        $fingerprint = $userId ? null : hash('sha256', $ip.request()->userAgent());

        $existing = PostLike::query()
            ->where('post_id', $postId)
            ->when($userId, fn ($q) => $q->where('user_id', $userId), fn ($q) => $q->where('fingerprint', $fingerprint))
            ->first();

        if ($existing) {
            $existing->delete();
            Post::whereKey($postId)->decrement('like_count');

            return false;
        }

        PostLike::query()->create([
            'post_id' => $postId,
            'user_id' => $userId,
            'fingerprint' => $fingerprint,
        ]);
        Post::whereKey($postId)->increment('like_count');

        return true;
    }

    public function toggleBookmark(int $postId, int $userId): bool
    {
        $existing = PostBookmark::query()->where('post_id', $postId)->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            Post::whereKey($postId)->decrement('bookmark_count');

            return false;
        }

        PostBookmark::query()->create(['post_id' => $postId, 'user_id' => $userId]);
        Post::whereKey($postId)->increment('bookmark_count');

        return true;
    }

    public function submitComment(int $postId, array $data, ?int $userId): Comment
    {
        $comment = Comment::query()->create([
            'post_id' => $postId,
            'user_id' => $userId,
            'parent_id' => $data['parent_id'] ?? null,
            'guest_name' => $data['guest_name'] ?? null,
            'guest_email' => $data['guest_email'] ?? null,
            'content' => $data['content'],
            'status' => 'pending',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        Post::whereKey($postId)->increment('comment_count');

        return $comment;
    }

    public function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (Post::query()->where('slug', $slug)->exists()) {
            $slug = "{$base}-".(++$i);
        }

        return $slug;
    }

    public function handlePublished(Post $post): void
    {
        if (! $post->published_at) {
            $post->update(['published_at' => now()]);
        }

        event(new PostPublished($post));
    }
}
