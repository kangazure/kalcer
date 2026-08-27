<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Mengelola pengaturan global website (key-value per grup) dengan layer
 * cache Redis, supaya pemanggilan berulang (di setiap request via
 * ViewServiceProvider untuk header/footer) tidak membebani database.
 */
class SettingService
{
    protected const CACHE_KEY_PUBLIC = 'settings:public';

    protected const CACHE_KEY_GROUP = 'settings:group:';

    /**
     * Mengambil seluruh setting yang ditandai is_public=true, dipakai
     * untuk data global di header/footer (alamat, telepon, social link).
     *
     * @return array<string, mixed>
     */
    public function getPublicSettings(): array
    {
        return Cache::remember(self::CACHE_KEY_PUBLIC, config('cache.ttl_presets.long'), function () {
            return Setting::query()
                ->public()
                ->get()
                ->groupBy('group_name')
                ->map(fn ($group) => $group->pluck('value', 'key'))
                ->toArray();
        });
    }

    /**
     * Mengambil seluruh setting dalam satu grup (termasuk yang privat),
     * dipakai oleh halaman admin Pengaturan Website.
     *
     * @return array<string, mixed>
     */
    public function getGroup(string $groupName): array
    {
        return Cache::remember(self::CACHE_KEY_GROUP.$groupName, config('cache.ttl_presets.medium'), function () use ($groupName) {
            return Setting::query()
                ->ofGroup($groupName)
                ->get()
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    public function get(string $group, string $key, mixed $default = null): mixed
    {
        return $this->getGroup($group)[$key] ?? $default;
    }

    /**
     * Membuat/memperbarui satu setting dan menginvalidasi cache terkait.
     */
    public function set(string $group, string $key, mixed $value, ?int $updatedByUserId = null): Setting
    {
        $setting = Setting::query()->updateOrCreate(
            ['group_name' => $group, 'key' => $key],
            ['value' => $value, 'updated_by' => $updatedByUserId]
        );

        $this->flushCache($group);

        Log::info("Setting [{$group}.{$key}] diperbarui.", ['updated_by' => $updatedByUserId]);

        return $setting;
    }

    /**
     * Update banyak setting sekaligus dalam satu grup (dipakai form
     * pengaturan admin yang submit semua field grup tertentu sekaligus).
     *
     * @param array<string, mixed> $values
     */
    public function setMany(string $group, array $values, ?int $updatedByUserId = null): void
    {
        foreach ($values as $key => $value) {
            Setting::query()->updateOrCreate(
                ['group_name' => $group, 'key' => $key],
                ['value' => $value, 'updated_by' => $updatedByUserId]
            );
        }

        $this->flushCache($group);
    }

    protected function flushCache(string $group): void
    {
        Cache::forget(self::CACHE_KEY_PUBLIC);
        Cache::forget(self::CACHE_KEY_GROUP.$group);
    }
}
