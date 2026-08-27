# Supabase Database — PT Jaringan Teknologi Sejahtera (JTS)

Folder ini berisi seluruh definisi database PostgreSQL yang dikelola
melalui Supabase: schema, RLS policy, trigger, function, view, realtime
publication, dan storage bucket.

## Struktur

```
supabase/
├── config.toml                  # konfigurasi Supabase CLI (local dev)
├── migrations/                  # SQL migration, dijalankan berurutan sesuai nomor
│   ├── 0001_extensions_and_types.sql
│   ├── 0002_auth_and_rbac.sql
│   ├── 0003_blog_module.sql
│   ├── 0004_media_gallery_portfolio_team.sql
│   ├── 0005_services_and_packages.sql
│   ├── 0006_career_module.sql
│   ├── 0007_contact_faq_testimonial_subscriber.sql
│   ├── 0008_promo_content.sql
│   ├── 0009_system_tables.sql
│   ├── 0010_isp_network_modules.sql
│   ├── 0011_row_level_security.sql
│   ├── 0012_realtime_publication.sql
│   ├── 0013_storage_buckets.sql
│   └── 0014_seed_initial_data.sql
├── functions/                   # Supabase Edge Functions (Deno/TypeScript)
└── policies/                    # dokumentasi tambahan policy (referensi)
```

## Cara Deploy ke Project Supabase

### Opsi A — Supabase CLI (disarankan)

```bash
# 1. Install Supabase CLI
npm install -g supabase

# 2. Login & link ke project
supabase login
supabase link --project-ref <project-ref-anda>

# 3. Push seluruh migration ke database remote
supabase db push
```

### Opsi B — Manual via SQL Editor (Supabase Dashboard)

Jalankan setiap file di `migrations/` secara berurutan (0001 → 0014)
melalui menu **SQL Editor** di Supabase Dashboard. **Urutan wajib
dipatuhi** karena ada dependency antar migration (contoh: RLS policy di
`0011` membutuhkan tabel yang dibuat di `0002`–`0010`).

## Catatan Penting

1. **Connection pooling**: gunakan port `6543` (Supavisor, mode
   `transaction`) untuk koneksi runtime aplikasi Laravel, dan port
   `5432` (direct connection) khusus saat menjalankan migration dari
   sisi Laravel (`php artisan migrate`) karena beberapa statement DDL
   (mis. `alter publication`) tidak kompatibel dengan pooler mode
   transaction.

2. **service_role key** dipakai oleh backend Laravel untuk operasi yang
   perlu bypass RLS (contoh: job scheduler, sinkronisasi network
   monitor). **Jangan pernah** expose `service_role` key ke frontend.

3. Tabel `public.users` adalah profil aplikasi, **bukan** tabel auth
   utama. Kredensial login dikelola oleh `auth.users` milik Supabase
   Auth dan terhubung melalui kolom `users.auth_user_id`.

4. Storage bucket `documents` bersifat privat (CV pelamar kerja).
   Akses baca hanya untuk role `super_admin`/`admin` melalui signed
   URL yang digenerate backend.

5. Setelah migration awal selesai, jalankan Laravel database seeder
   (`php artisan db:seed`) untuk mengisi data konten contoh (artikel,
   paket, portfolio, testimoni) — data ini terpisah dari seed RBAC/
   settings yang sudah include di `0014_seed_initial_data.sql`.
