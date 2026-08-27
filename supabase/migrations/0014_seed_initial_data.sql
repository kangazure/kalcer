-- =====================================================================
-- Migration: 0014_seed_initial_data.sql
-- Tujuan   : Data awal wajib agar sistem RBAC & settings langsung
--            berfungsi setelah migration dijalankan (bukan data dummy
--            konten — itu ditangani oleh Laravel database seeder).
-- =====================================================================

-- ---------------------------------------------------------------------
-- SEED: roles (5 role sesuai requirement)
-- ---------------------------------------------------------------------
insert into public.roles (name, slug, description, is_system) values
    ('Super Admin', 'super_admin', 'Akses penuh ke seluruh sistem, termasuk pengaturan global dan manajemen pengguna.', true),
    ('Admin', 'admin', 'Mengelola konten, layanan, paket, dan operasional harian website.', true),
    ('Editor', 'editor', 'Mengelola artikel blog, kategori, tag, dan moderasi komentar.', true),
    ('Marketing', 'marketing', 'Mengelola banner, slider, promo, testimoni, dan analitik pemasaran.', true),
    ('Operator', 'operator', 'Mengelola monitoring jaringan, coverage area, maintenance, dan laporan gangguan.', true)
on conflict (slug) do nothing;

-- ---------------------------------------------------------------------
-- SEED: permissions (granular per modul)
-- ---------------------------------------------------------------------
insert into public.permissions (name, slug, module, description) values
    -- Posts
    ('Lihat Artikel', 'posts.view', 'posts', 'Melihat daftar dan detail artikel'),
    ('Buat Artikel', 'posts.create', 'posts', 'Membuat artikel baru'),
    ('Ubah Artikel', 'posts.update', 'posts', 'Mengubah artikel'),
    ('Hapus Artikel', 'posts.delete', 'posts', 'Menghapus artikel'),
    ('Publikasi Artikel', 'posts.publish', 'posts', 'Mempublikasikan artikel'),
    -- Categories & Tags
    ('Kelola Kategori', 'categories.manage', 'categories', 'CRUD kategori artikel'),
    ('Kelola Tag', 'tags.manage', 'tags', 'CRUD tag artikel'),
    -- Comments
    ('Moderasi Komentar', 'comments.moderate', 'comments', 'Menyetujui/menolak komentar'),
    -- Services & Packages
    ('Kelola Layanan', 'services.manage', 'services', 'CRUD layanan ISP'),
    ('Kelola Paket', 'packages.manage', 'packages', 'CRUD paket internet'),
    -- Portfolio & Gallery & Team
    ('Kelola Portfolio', 'portfolio.manage', 'portfolio', 'CRUD portfolio proyek'),
    ('Kelola Galeri', 'gallery.manage', 'gallery', 'CRUD galeri foto'),
    ('Kelola Tim', 'team.manage', 'team', 'CRUD susunan tim'),
    -- Career
    ('Kelola Lowongan', 'career.manage', 'career', 'CRUD lowongan kerja'),
    ('Lihat Lamaran', 'job_application.view', 'career', 'Melihat lamaran masuk'),
    ('Proses Lamaran', 'job_application.process', 'career', 'Mengubah status lamaran'),
    -- Contact & Testimonial & FAQ
    ('Kelola Kontak', 'contact.manage', 'contact', 'Melihat dan menindaklanjuti pesan kontak'),
    ('Kelola Testimoni', 'testimonial.manage', 'testimonial', 'CRUD testimoni pelanggan'),
    ('Kelola FAQ', 'faq.manage', 'faq', 'CRUD FAQ'),
    -- Promo Content
    ('Kelola Banner', 'banner.manage', 'banner', 'CRUD banner'),
    ('Kelola Slider', 'slider.manage', 'slider', 'CRUD slider hero'),
    ('Kelola Popup', 'popup.manage', 'popup', 'CRUD popup promosi'),
    ('Kelola Pengumuman', 'announcement.manage', 'announcement', 'CRUD pengumuman global'),
    -- Network/ISP
    ('Kelola Coverage Area', 'coverage_area.manage', 'network', 'CRUD wilayah jangkauan'),
    ('Kelola Network Monitor', 'network_monitor.manage', 'network', 'CRUD node monitoring jaringan'),
    ('Kelola Maintenance', 'maintenance.manage', 'network', 'CRUD jadwal maintenance'),
    ('Kelola Gangguan', 'trouble_report.manage', 'network', 'Memproses laporan gangguan'),
    -- Users & Settings
    ('Kelola Pengguna', 'users.manage', 'users', 'CRUD pengguna admin'),
    ('Kelola Pengaturan', 'settings.manage', 'settings', 'Mengubah pengaturan global website'),
    ('Lihat Log Aktivitas', 'activity_logs.view', 'system', 'Melihat audit trail sistem'),
    ('Lihat Analitik', 'analytics.view', 'system', 'Melihat statistik pengunjung & analitik'),
    ('Backup Database', 'backup.manage', 'system', 'Menjalankan dan mengunduh backup database')
on conflict (slug) do nothing;

-- ---------------------------------------------------------------------
-- SEED: permission_role mapping
-- ---------------------------------------------------------------------
-- Super Admin: SEMUA permission.
insert into public.permission_role (permission_id, role_id)
select p.id, r.id
from public.permissions p
cross join public.roles r
where r.slug = 'super_admin'
on conflict do nothing;

-- Admin: semua kecuali kelola pengguna & pengaturan global.
insert into public.permission_role (permission_id, role_id)
select p.id, r.id
from public.permissions p
cross join public.roles r
where r.slug = 'admin'
  and p.slug not in ('users.manage', 'settings.manage', 'backup.manage')
on conflict do nothing;

-- Editor: modul konten blog & komentar.
insert into public.permission_role (permission_id, role_id)
select p.id, r.id
from public.permissions p
cross join public.roles r
where r.slug = 'editor'
  and p.module in ('posts', 'categories', 'tags', 'comments', 'faq')
on conflict do nothing;

-- Marketing: promo content, testimoni, analitik, portfolio, gallery.
insert into public.permission_role (permission_id, role_id)
select p.id, r.id
from public.permissions p
cross join public.roles r
where r.slug = 'marketing'
  and (
        p.module in ('banner', 'slider', 'popup', 'testimonial', 'portfolio', 'gallery')
        or p.slug in ('analytics.view', 'packages.manage', 'services.manage')
      )
on conflict do nothing;

-- Operator: modul network/ISP.
insert into public.permission_role (permission_id, role_id)
select p.id, r.id
from public.permissions p
cross join public.roles r
where r.slug = 'operator'
  and p.module = 'network'
on conflict do nothing;

-- ---------------------------------------------------------------------
-- SEED: settings (konfigurasi default berdasarkan data resmi PT JTS)
-- ---------------------------------------------------------------------
insert into public.settings (group_name, key, value, label, is_public) values
    ('general', 'company_legal_name', '"PT. Jaringan Teknologi Sejahtera"', 'Nama Resmi Perusahaan', true),
    ('general', 'company_brand_name', '"JTS"', 'Nama Brand', true),
    ('general', 'company_tagline', '"Internet Service Provider"', 'Tagline', true),
    ('general', 'company_address', '"Dusun 1 Suko Rini, Rt/Rw 002/001, Desa Rukti Sedyo, Kec. Raman Utara, Kab. Lampung Timur, Kode Pos 34371"', 'Alamat Kantor', true),
    ('general', 'company_phone', '"+6282183999981"', 'Nomor Telepon', true),
    ('general', 'company_whatsapp', '"+6282183999981"', 'Nomor WhatsApp', true),
    ('general', 'company_email', '"info@ptjts.id"', 'Email Resmi', true),
    ('general', 'company_founded_date', '"2024-06-06"', 'Tanggal Pendirian (Akta Notaris)', true),
    ('general', 'company_notary_info', '"Akta Notaris No.836 tanggal 06 Juni 2024 dari Santy Sagita, S.H., M.Kn., Notaris di Kota Cilegon. Disahkan Menteri Hukum RI No. AHU-0111314.AH.01.11.TAHUN 2024."', 'Informasi Legalitas', true),
    ('seo', 'default_meta_title', '"PT Jaringan Teknologi Sejahtera (JTS) — Internet Service Provider Lampung Timur"', 'Default Meta Title', true),
    ('seo', 'default_meta_description', '"JTS adalah penyedia jasa internet fiber optik cepat dan andal untuk rumah, bisnis, dan korporat di Kabupaten Lampung Timur."', 'Default Meta Description', true),
    ('social', 'instagram_url', '"https://instagram.com/ptjts.id"', 'Instagram', true),
    ('social', 'facebook_url', '"https://facebook.com/ptjts.id"', 'Facebook', true),
    ('integration', 'maps_default_lat', '-5.0667', 'Latitude Default Peta', false),
    ('integration', 'maps_default_lng', '105.5333', 'Longitude Default Peta', false)
on conflict (group_name, key) do nothing;
