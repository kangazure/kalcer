<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface PostRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Daftar artikel published, dengan dukungan filter kategori/tag dan
     * infinite scroll (cursor/offset pagination) untuk halaman blog publik.
     */
    public function paginatePublished(array $filters = [], ?string $keyword = null, int $perPage = 10): LengthAwarePaginator;

    public function findPublishedBySlug(string $slug): ?Model;

    public function featured(int $limit = 5): Collection;

    public function trending(int $limit = 5): Collection;

    public function popular(int $limit = 5): Collection;

    public function recent(int $limit = 5): Collection;

    public function related(int $postId, int $limit = 4): Collection;

    public function incrementViewCount(int $postId): void;

    public function toggleLike(int $postId, ?int $userId, ?string $fingerprint): bool;

    public function toggleBookmark(int $postId, int $userId): bool;
}
