<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Base Model untuk seluruh model aplikasi JTS.
 *
 * Menetapkan koneksi database default ke 'pgsql' (Supabase Postgres via
 * connection pooler), karena Laravel secara default akan memakai koneksi
 * 'default' dari config/database.php — namun dideklarasikan eksplisit di
 * sini agar jelas dan mudah di-override saat testing (sqlite_testing).
 *
 * Juga otomatis mengisi kolom `uuid` (jika ada pada tabel model) saat
 * record baru dibuat. PENTING: migration SENGAJA tidak memberi default
 * value SQL untuk kolom uuid (mis. default(Str::uuid())), karena nilai
 * semacam itu hanya dievaluasi SEKALI saat schema dibuat -- bukan per
 * baris -- sehingga akan menyebabkan duplicate key violation dari baris
 * kedua dan seterusnya. Auto-generate di level aplikasi (di sini) adalah
 * pendekatan yang benar dan konsisten dengan seluruh seeder/factory.
 */
abstract class BaseModel extends EloquentModel
{
    /**
     * @var string
     */
    protected $connection = 'pgsql';

    /**
     * Cache hasil pengecekan "apakah tabel X punya kolom uuid" per nama
     * tabel, supaya tidak melakukan query Schema::hasColumn() berulang
     * kali untuk tabel yang sama dalam satu request/proses.
     *
     * @var array<string, bool>
     */
    protected static array $uuidColumnCache = [];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (EloquentModel $model) {
            if (static::tableHasUuidColumn($model) && empty($model->getAttribute('uuid'))) {
                $model->setAttribute('uuid', (string) Str::uuid());
            }
        });
    }

    protected static function tableHasUuidColumn(EloquentModel $model): bool
    {
        $table = $model->getTable();

        if (! array_key_exists($table, static::$uuidColumnCache)) {
            static::$uuidColumnCache[$table] = Schema::connection($model->getConnectionName())->hasColumn($table, 'uuid');
        }

        return static::$uuidColumnCache[$table];
    }
}
