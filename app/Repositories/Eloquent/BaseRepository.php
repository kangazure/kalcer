<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Implementasi dasar repository berbasis Eloquent. Setiap repository modul
 * (PostRepository, PackageRepository, dst) extends class ini dan hanya
 * perlu mendefinisikan $model serta override method yang butuh query
 * khusus (eager-load relasi, scope tambahan, dsb).
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    /**
     * Kolom yang boleh dipakai untuk pencarian keyword (ILIKE) pada
     * method search(). Override di repository turunan.
     *
     * @var array<int, string>
     */
    protected array $searchableColumns = [];

    /**
     * Kolom yang boleh dipakai untuk filter exact-match dinamis pada
     * method search(), contoh: ['status', 'category_id'].
     *
     * @var array<int, string>
     */
    protected array $filterableColumns = [];

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->newQuery()->select($columns)->get();
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->newQuery()->select($columns)->latest()->paginate($perPage);
    }

    public function find(int|string $id, array $columns = ['*']): ?Model
    {
        return $this->model->newQuery()->select($columns)->find($id);
    }

    public function findOrFail(int|string $id, array $columns = ['*']): Model
    {
        return $this->model->newQuery()->select($columns)->findOrFail($id);
    }

    public function findBySlug(string $slug): ?Model
    {
        return $this->model->newQuery()->where('slug', $slug)->first();
    }

    public function create(array $attributes): Model
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function update(int|string $id, array $attributes): Model
    {
        $record = $this->findOrFail($id);
        $record->update($attributes);

        return $record->refresh();
    }

    public function delete(int|string $id): bool
    {
        $record = $this->findOrFail($id);

        return (bool) $record->delete();
    }

    public function search(array $filters = [], ?string $keyword = null, ?string $sort = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        $query = $this->applyFilters($query, $filters);
        $query = $this->applyKeywordSearch($query, $keyword);
        $query = $this->applySorting($query, $sort);

        return $query->paginate($perPage)->appends(array_merge($filters, [
            'search' => $keyword,
            'sort' => $sort,
        ]));
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        foreach ($filters as $column => $value) {
            if (! in_array($column, $this->filterableColumns, true) || $value === null || $value === '') {
                continue;
            }

            if (is_array($value)) {
                $query->whereIn($column, $value);
            } else {
                $query->where($column, $value);
            }
        }

        return $query;
    }

    protected function applyKeywordSearch(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword) || empty($this->searchableColumns)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($keyword) {
            foreach ($this->searchableColumns as $column) {
                $q->orWhere($column, 'ilike', "%{$keyword}%");
            }
        });
    }

    protected function applySorting(Builder $query, ?string $sort): Builder
    {
        if (blank($sort)) {
            return $query->latest();
        }

        // Format: "-created_at" untuk descending, "created_at" untuk ascending.
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');

        $allowedSortColumns = array_merge($this->filterableColumns, $this->searchableColumns, ['created_at', 'updated_at', 'sort_order']);

        if (! in_array($column, $allowedSortColumns, true)) {
            return $query->latest();
        }

        return $query->orderBy($column, $direction);
    }
}
