<?php

namespace App\Repositories\Eloquent;

use App\Models\Post;
use App\Models\PostBookmark;
use App\Models\PostLike;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    protected array $searchableColumns = ['title', 'excerpt', 'content'];

    protected array $filterableColumns = ['category_id', 'author_id', 'status', 'is_featured'];

    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    public function paginatePublished(array $filters = [], ?string $keyword = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->published()
            ->with(['category:id,name,slug', 'author:id,name,avatar_url', 'tags:id,name,slug'])
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at');

        if (! empty($filters['category_slug'])) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $filters['category_slug']));
        }

        if (! empty($filters['tag_slug'])) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $filters['tag_slug']));
        }

        if (! blank($keyword)) {
            $query = $this->applyKeywordSearch($query, $keyword);
        }

        return $query->paginate($perPage)->appends($filters);
    }

    public function findPublishedBySlug(string $slug): ?Model
    {
        return $this->model->newQuery()
            ->published()
            ->with(['category', 'author', 'tags', 'approvedComments.user'])
            ->where('slug', $slug)
            ->first();
    }

    public function featured(int $limit = 5): Collection
    {
        return $this->model->newQuery()
            ->published()
            ->featured()
            ->with(['category:id,name,slug'])
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function trending(int $limit = 5): Collection
    {
        return $this->model->newQuery()
            ->published()
            ->where('published_at', '>=', now()->subDays(30))
            ->orderByRaw('(view_count * 1 + like_count * 3 + comment_count * 5 + share_count * 4) desc')
            ->limit($limit)
            ->get();
    }

    public function popular(int $limit = 5): Collection
    {
        return $this->model->newQuery()
            ->published()
            ->orderByDesc('view_count')
            ->limit($limit)
            ->get();
    }

    public function recent(int $limit = 5): Collection
    {
        return $this->model->newQuery()
            ->published()
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function related(int $postId, int $limit = 4): Collection
    {
        $post = $this->model->newQuery()->find($postId);

        if (! $post) {
            return new Collection;
        }

        $tagIds = $post->tags()->pluck('tags.id');

        return $this->model->newQuery()
            ->published()
            ->where('id', '!=', $postId)
            ->where(function ($q) use ($post, $tagIds) {
                $q->where('category_id', $post->category_id);

                if ($tagIds->isNotEmpty()) {
                    $q->orWhereHas('tags', fn ($tq) => $tq->whereIn('tags.id', $tagIds));
                }
            })
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function incrementViewCount(int $postId): void
    {
        // Increment atomik di level DB, hindari race condition pada traffic tinggi.
        $this->model->newQuery()->where('id', $postId)->increment('view_count');
    }

    public function toggleLike(int $postId, ?int $userId, ?string $fingerprint): bool
    {
        return DB::transaction(function () use ($postId, $userId, $fingerprint) {
            $query = PostLike::query()->where('post_id', $postId);

            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('fingerprint', $fingerprint);
            }

            $existing = $query->first();

            if ($existing) {
                $existing->delete();

                return false; // unliked
            }

            PostLike::query()->create([
                'post_id' => $postId,
                'user_id' => $userId,
                'fingerprint' => $userId ? null : $fingerprint,
            ]);

            return true; // liked
        });
    }

    public function toggleBookmark(int $postId, int $userId): bool
    {
        return DB::transaction(function () use ($postId, $userId) {
            $existing = PostBookmark::query()
                ->where('post_id', $postId)
                ->where('user_id', $userId)
                ->first();

            if ($existing) {
                $existing->delete();

                return false;
            }

            PostBookmark::query()->create(['post_id' => $postId, 'user_id' => $userId]);

            return true;
        });
    }
}
