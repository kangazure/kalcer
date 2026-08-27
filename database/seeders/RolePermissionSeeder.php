<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'description' => 'Akses penuh ke seluruh sistem.', 'is_system' => true],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Mengelola konten dan operasional harian.', 'is_system' => true],
            ['name' => 'Editor', 'slug' => 'editor', 'description' => 'Mengelola artikel blog dan moderasi komentar.', 'is_system' => true],
            ['name' => 'Marketing', 'slug' => 'marketing', 'description' => 'Mengelola banner, promo, dan analitik pemasaran.', 'is_system' => true],
            ['name' => 'Operator', 'slug' => 'operator', 'description' => 'Mengelola monitoring jaringan dan laporan gangguan.', 'is_system' => true],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }

        $this->command->info('Roles seeded: '.count($roles).' entries.');

        $permissions = [
            ['name' => 'Lihat Artikel', 'slug' => 'posts.view', 'module' => 'posts'],
            ['name' => 'Buat Artikel', 'slug' => 'posts.create', 'module' => 'posts'],
            ['name' => 'Ubah Artikel', 'slug' => 'posts.update', 'module' => 'posts'],
            ['name' => 'Hapus Artikel', 'slug' => 'posts.delete', 'module' => 'posts'],
            ['name' => 'Publikasi Artikel', 'slug' => 'posts.publish', 'module' => 'posts'],
            ['name' => 'Kelola Kategori', 'slug' => 'categories.manage', 'module' => 'categories'],
            ['name' => 'Kelola Tag', 'slug' => 'tags.manage', 'module' => 'tags'],
            ['name' => 'Moderasi Komentar', 'slug' => 'comments.moderate', 'module' => 'comments'],
            ['name' => 'Kelola Layanan', 'slug' => 'services.manage', 'module' => 'services'],
            ['name' => 'Kelola Paket', 'slug' => 'packages.manage', 'module' => 'packages'],
            ['name' => 'Kelola Portfolio', 'slug' => 'portfolio.manage', 'module' => 'portfolio'],
            ['name' => 'Kelola Galeri', 'slug' => 'gallery.manage', 'module' => 'gallery'],
            ['name' => 'Kelola Tim', 'slug' => 'team.manage', 'module' => 'team'],
            ['name' => 'Kelola Lowongan', 'slug' => 'career.manage', 'module' => 'career'],
            ['name' => 'Lihat Lamaran', 'slug' => 'job_application.view', 'module' => 'career'],
            ['name' => 'Proses Lamaran', 'slug' => 'job_application.process', 'module' => 'career'],
            ['name' => 'Kelola Kontak', 'slug' => 'contact.manage', 'module' => 'contact'],
            ['name' => 'Kelola Testimoni', 'slug' => 'testimonial.manage', 'module' => 'testimonial'],
            ['name' => 'Kelola FAQ', 'slug' => 'faq.manage', 'module' => 'faq'],
            ['name' => 'Kelola Banner', 'slug' => 'banner.manage', 'module' => 'banner'],
            ['name' => 'Kelola Slider', 'slug' => 'slider.manage', 'module' => 'slider'],
            ['name' => 'Kelola Popup', 'slug' => 'popup.manage', 'module' => 'popup'],
            ['name' => 'Kelola Pengumuman', 'slug' => 'announcement.manage', 'module' => 'announcement'],
            ['name' => 'Kelola Coverage Area', 'slug' => 'coverage_area.manage', 'module' => 'network'],
            ['name' => 'Kelola Network Monitor', 'slug' => 'network_monitor.manage', 'module' => 'network'],
            ['name' => 'Kelola Maintenance', 'slug' => 'maintenance.manage', 'module' => 'network'],
            ['name' => 'Kelola Gangguan', 'slug' => 'trouble_report.manage', 'module' => 'network'],
            ['name' => 'Kelola Pengguna', 'slug' => 'users.manage', 'module' => 'users'],
            ['name' => 'Kelola Pengaturan', 'slug' => 'settings.manage', 'module' => 'settings'],
            ['name' => 'Lihat Log Aktivitas', 'slug' => 'activity_logs.view', 'module' => 'system'],
            ['name' => 'Lihat Analitik', 'slug' => 'analytics.view', 'module' => 'system'],
            ['name' => 'Backup Database', 'slug' => 'backup.manage', 'module' => 'system'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['slug' => $permission['slug']], $permission);
        }

        $this->command->info('Permissions seeded: '.count($permissions).' entries.');

        // Mapping permission ke role
        $superAdmin = Role::where('slug', 'super_admin')->first();
        $admin = Role::where('slug', 'admin')->first();
        $editor = Role::where('slug', 'editor')->first();
        $marketing = Role::where('slug', 'marketing')->first();
        $operator = Role::where('slug', 'operator')->first();

        \DB::table('permission_role')->insertOrIgnore(
            Permission::all()->map(fn ($p) => ['permission_id' => $p->id, 'role_id' => $superAdmin->id, 'created_at' => now()])->toArray()
        );

        \DB::table('permission_role')->insertOrIgnore(
            Permission::whereNotIn('slug', ['users.manage', 'settings.manage', 'backup.manage'])
                ->get()->map(fn ($p) => ['permission_id' => $p->id, 'role_id' => $admin->id, 'created_at' => now()])->toArray()
        );

        \DB::table('permission_role')->insertOrIgnore(
            Permission::whereIn('module', ['posts', 'categories', 'tags', 'comments', 'faq'])
                ->get()->map(fn ($p) => ['permission_id' => $p->id, 'role_id' => $editor->id, 'created_at' => now()])->toArray()
        );

        \DB::table('permission_role')->insertOrIgnore(
            Permission::whereIn('module', ['banner', 'slider', 'popup', 'testimonial', 'portfolio', 'gallery'])
                ->orWhereIn('slug', ['analytics.view', 'packages.manage', 'services.manage'])
                ->get()->map(fn ($p) => ['permission_id' => $p->id, 'role_id' => $marketing->id, 'created_at' => now()])->toArray()
        );

        \DB::table('permission_role')->insertOrIgnore(
            Permission::where('module', 'network')->get()->map(fn ($p) => ['permission_id' => $p->id, 'role_id' => $operator->id, 'created_at' => now()])->toArray()
        );

        $this->command->info('Permission-role mapping selesai.');
    }
}
