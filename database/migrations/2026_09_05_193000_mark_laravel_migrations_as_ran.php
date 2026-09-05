<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tandai migrasi Laravel yang sudah dibuat oleh SQL Supabase
     * agar tidak mencoba create ulang tabel yang sudah ada.
     */
    public function up(): void
    {
        // Tabel ini sudah dibuat oleh Supabase SQL migration (0001 & 0002)
        DB::table('migrations')->insert([
            ['migration' => '2024_06_01_000001_create_roles_and_permissions_table', 'batch' => 99],
            ['migration' => '2024_06_01_000002_create_users_table',             'batch' => 99],
            ['migration' => '2024_06_01_000003_create_content_tables',         'batch' => 99],
        ]);
    }

    public function down(): void
    {
        DB::table('migrations')
            ->whereIn('migration', [
                '2024_06_01_000001_create_roles_and_permissions_table',
                '2024_06_01_000002_create_users_table',
                '2024_06_01_000003_create_content_tables',
            ])
            ->delete();
    }
};
