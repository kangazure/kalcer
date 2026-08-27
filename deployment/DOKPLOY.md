# Panduan Deploy ke Dokploy — PT Jaringan Teknologi Sejahtera

Dokploy adalah platform self-hosted (mirip Vercel/Heroku) yang menjalankan aplikasi via Docker secara otomatis. Panduan ini jauh lebih ringkas dibanding setup manual VPS karena Dokploy mengurus Nginx, SSL, dan restart otomatis untuk Anda.

## Prasyarat

- Server VPS (minimal 2GB RAM) dengan Dokploy sudah terinstall — kalau belum, install dulu:
  ```bash
  curl -sSL https://dokploy.com/install.sh | sh
  ```
- Project sudah di-push ke Git repository (GitHub/GitLab/Gitea) — Dokploy build dari Git, bukan upload zip manual.
- Akun Supabase (untuk database PostgreSQL) — sama seperti setup local.

## 1. Push Project ke Git Repository

```bash
cd ptjts
git init
git add .
git commit -m "Initial commit - PT JTS website"
git remote add origin https://github.com/username/ptjts.git
git push -u origin main
```

> **Penting**: pastikan `.env` **tidak** ikut ter-commit (sudah ada di `.gitignore` bawaan Laravel). Kredensial akan diisi langsung di dashboard Dokploy, bukan lewat file.

## 2. Buat Aplikasi Baru di Dokploy

1. Login ke dashboard Dokploy Anda (`https://your-server-ip:3000`)
2. **Create Project** → beri nama `ptjts`
3. Di dalam project, klik **Create Service** → pilih **Application**
4. **Source Type**: pilih `Git Provider`, hubungkan ke repository yang tadi di-push
5. **Build Type**: pilih `Dockerfile` (Dokploy akan otomatis pakai `Dockerfile` yang sudah ada di root project ini)

## 3. Set Environment Variables

Di tab **Environment** pada service Dokploy, paste seluruh isi `.env.example` lalu isi nilai aslinya, minimal:

```
APP_NAME="PT Jaringan Teknologi Sejahtera"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://rikoardianto.web.id

DB_CONNECTION=pgsql
DB_HOST=db.xxxxxxxxxxxx.supabase.co
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=<password Supabase Anda>

SUPABASE_URL=https://xxxxxxxxxxxx.supabase.co
SUPABASE_ANON_KEY=<dari Supabase>
SUPABASE_SERVICE_ROLE_KEY=<dari Supabase>

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=<nama service redis, lihat langkah 4>
```

> Jangan isi `APP_KEY` manual — biarkan kosong, `entrypoint.sh` akan generate otomatis saat container pertama kali start.

## 4. Tambahkan Redis (opsional tapi disarankan untuk production)

1. Di project Dokploy yang sama, **Create Service** → pilih **Database** → **Redis**
2. Beri nama misalnya `ptjts-redis`
3. Setelah jalan, salin **internal hostname**-nya (biasanya sama dengan nama service) ke `REDIS_HOST` di environment variable aplikasi Anda (langkah 3)

## 5. Setup Domain & SSL

1. Di tab **Domains** pada service aplikasi, klik **Add Domain**
2. Masukkan `ptjts.id` (dan `www.ptjts.id` jika perlu, sebagai domain terpisah)
3. Arahkan DNS domain Anda (A record) ke IP server Dokploy
4. Aktifkan toggle **HTTPS** — Dokploy otomatis generate sertifikat Let's Encrypt

## 6. Deploy

Klik tombol **Deploy** di dashboard. Dokploy akan:
1. Clone repository
2. Build image dari `Dockerfile` (compile assets, install composer, dll — sesuai yang sudah didefinisikan)
3. Jalankan container — `entrypoint.sh` otomatis generate `APP_KEY`, cache config, dan migrate database
4. Expose aplikasi di domain yang sudah diset

Pantau progresnya di tab **Deployments** — kalau ada error, log build akan muncul di situ.

## 7. Setup Database Supabase (jika belum)

Sama seperti panduan local — buka **SQL Editor** di dashboard Supabase, jalankan seluruh file `supabase/migrations/` berurutan (0001 → 0014) sebelum atau sesudah deploy pertama (migration Laravel di `entrypoint.sh` hanya mensinkronkan tabel tambahan, bukan pengganti SQL migration Supabase).

## 8. Verifikasi

- Buka `https://rikoardianto.web.id` — pastikan homepage tampil
- Cek log container di tab **Logs** Dokploy kalau ada masalah
- Login admin: `https://rikoardianto.web.id/admin/login` — **segera ganti password default**

## 9. Auto-Deploy saat Push (opsional)

Di tab **General** service, aktifkan **Auto Deploy** dan hubungkan webhook — setiap `git push` ke branch `main` akan otomatis trigger build & deploy baru.

## Troubleshooting Dokploy

| Masalah | Solusi |
|---|---|
| Build gagal di tahap `npm run build` | Cek log build, biasanya karena `node_modules` cache lama — coba **Rebuild** dari awal (clear cache) |
| Container restart terus (crash loop) | Cek tab Logs — biasanya `DB_HOST`/`DB_PASSWORD` salah, container gagal connect ke Supabase saat migrate |
| 502 dari domain | Container belum fully up (masih migrate/cache) — tunggu 30-60 detik, atau cek log `entrypoint.sh` |
| Asset CSS/JS 404 | Pastikan stage `frontend` di Dockerfile berhasil generate `public/build` — cek build log tahap tersebut |
