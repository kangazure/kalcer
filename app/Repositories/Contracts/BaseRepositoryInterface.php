<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Kontrak dasar yang wajib dipenuhi seluruh repository aplikasi.
 *
 * Pola Repository dipakai agar Controller/Service tidak bergantung langsung
 * pada Eloquent Query Builder, sehingga sumber data (Eloquent ke Supabase
 * Postgres, atau suatu saat langsung ke Supabase REST/PostgREST) dapat
 * diganti tanpa mengubah kode pemanggil.
 */
interface BaseRepositoryInterface
{
    public function all(array $columns = ['*']): Collection;

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    public function find(int|string $id, array $columns = ['*']): ?Model;

    public function findOrFail(int|string $id, array $columns = ['*']): Model;

    public function findBySlug(string $slug): ?Model;

    public function create(array $attributes): Model;

    public function update(int|string $id, array $attributes): Model;

    public function delete(int|string $id): bool;

    /**
     * Pencarian dengan filter dinamis, dipakai endpoint REST API yang
     * mendukung query parameter `search`, `filter[...]`, `sort`.
     */
    public function search(array $filters = [], ?string $keyword = null, ?string $sort = null, int $perPage = 15): LengthAwarePaginator;
}
