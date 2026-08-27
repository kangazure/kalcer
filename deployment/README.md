# Panduan Deploy — PT Jaringan Teknologi Sejahtera (JTS)

Panduan ini untuk deploy ke server **Ubuntu Server 22.04/24.04** menggunakan Nginx + PHP-FPM + Redis + Supervisor + Supabase (PostgreSQL).

## 1. Prasyarat Server

```bash
sudo apt update && sudo apt upgrade -y

# PHP 8.3 + ekstensi yang dibutuhkan
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.3-fpm php8.3-cli php8.3-pgsql php8.3-redis \
    php8.3-mbstring php8.3-xml php8.3-curl php8.3-gd php8.3-zip php8.3-bcmath

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Node.js 20 LTS + npm
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Redis, Nginx, Supervisor, Certbot
sudo apt install -y redis-server nginx supervisor certbot python3-certbot-nginx
```

## 2. Clone / Upload Project

```bash
sudo mkdir -p /var/www/ptjts
# Upload/extract file project ke /var/www/ptjts (via scp, git clone, atau unzip)
cd /var/www/ptjts
```

## 3. Install Dependency

```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

## 4. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` dan isi minimal:
- `APP_URL=https://rikoardianto.web.id`
- `DB_HOST`, `DB_PASSWORD`, `SUPABASE_URL`, `SUPABASE_ANON_KEY`, `SUPABASE_SERVICE_ROLE_KEY` (dari Supabase Dashboard project Anda)
- `REDIS_HOST` (biasanya `127.0.0.1` jika Redis di server yang sama)
- `MAIL_*`, `WHATSAPP_*`, `TELEGRAM_*` sesuai kredensial integrasi Anda

## 5. Setup Database Supabase

Jalankan seluruh SQL migration di `supabase/migrations/` secara berurutan (0001 → 0014) melalui **Supabase SQL Editor** di dashboard, atau via Supabase CLI (`supabase db push`). Lihat `supabase/README.md` untuk detail.

Setelah schema Supabase siap, sinkronkan tabel migrasi Laravel (tanpa re-run DDL):

```bash
php artisan migrate --pretend   # opsional, cek dulu SQL yang akan dijalankan
php artisan db:seed             # isi data awal (roles, permissions, konten placeholder)
```

## 6. Permission Folder

```bash
sudo chown -R www-data:www-data /var/www/ptjts
sudo find /var/www/ptjts -type f -exec chmod 644 {} \;
sudo find /var/www/ptjts -type d -exec chmod 755 {} \;
sudo chmod -R 775 storage bootstrap/cache
```

## 7. Konfigurasi Nginx (2 Tahap)

**Tahap 1 — Deploy config HTTP-only dulu** (wajib, supaya certbot bisa memvalidasi domain dan Nginx tidak error mencari sertifikat yang belum ada):

```bash
sudo cp deployment/nginx/ptjts.id.http-only.conf /etc/nginx/sites-available/ptjts.id
sudo ln -s /etc/nginx/sites-available/ptjts.id /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

Pastikan domain Anda (DNS A record) sudah mengarah ke IP server ini sebelum lanjut ke tahap berikut.

**Tahap 2 — Dapatkan sertifikat SSL:**

```bash
sudo certbot certonly --nginx -d ptjts.id -d www.ptjts.id
```

**Tahap 3 — Ganti ke config full HTTPS:**

```bash
sudo cp deployment/nginx/ptjts.id.ssl.conf /etc/nginx/sites-available/ptjts.id
sudo nginx -t
sudo systemctl reload nginx
```

> Kalau `nginx -t` di Tahap 3 masih error "cannot load certificate", cek dulu apakah file benar-benar ada: `sudo ls /etc/letsencrypt/live/ptjts.id/`. Kalau kosong, berarti Tahap 2 (certbot) belum berhasil — ulangi Tahap 2 dan pastikan domain sudah resolve ke server sebelum mencoba lagi.

## 8. Konfigurasi PHP-FPM Pool

```bash
sudo cp deployment/php-fpm-pool.conf /etc/php/8.3/fpm/pool.d/ptjts.conf
sudo systemctl restart php8.3-fpm
```

## 9. Konfigurasi Supervisor (Horizon Queue + Scheduler)

```bash
sudo cp deployment/supervisor/ptjts.conf /etc/supervisor/conf.d/ptjts.conf
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start ptjts:*
```

Cek status:

```bash
sudo supervisorctl status
```

## 10. Cron untuk Laravel Scheduler (alternatif jika tidak pakai Supervisor scheduler)

```bash
sudo crontab -e -u www-data
```

Tambahkan baris:

```
* * * * * cd /var/www/ptjts && php artisan schedule:run >> /dev/null 2>&1
```

> Catatan: gunakan **salah satu** — cron ATAU proses `ptjts-scheduler` di Supervisor, jangan keduanya sekaligus (akan duplikat eksekusi).

## 11. Optimasi Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

> Jalankan ulang perintah ini setiap kali ada perubahan kode di production (`config:clear` dulu sebelum `config:cache` jika sebelumnya sudah pernah di-cache).

## 12. Verifikasi

- Buka `https://rikoardianto.web.id` — homepage harus tampil.
- Buka `https://rikoardianto.web.id/admin/login` — login dengan akun dari `UserSeeder` (ingat: **ganti password default** setelah login pertama).
- Cek health endpoint: `https://rikoardianto.web.id/up` (Laravel health check bawaan).
- Cek log jika ada error: `tail -f storage/logs/laravel.log`

## 13. Checklist Keamanan Sebelum Go-Live

- [ ] Ganti seluruh password default di `UserSeeder` (super admin, admin, editor, marketing, operator)
- [ ] Set `APP_DEBUG=false` di `.env`
- [ ] Pastikan `.env` tidak bisa diakses publik (sudah diblok di config Nginx)
- [ ] Set kredensial Supabase asli (`SUPABASE_SERVICE_ROLE_KEY` jangan pernah diekspos ke frontend)
- [ ] Aktifkan Cloudflare Turnstile atau reCAPTCHA (`CLOUDFLARE_TURNSTILE_*` di `.env`)
- [ ] Setup backup otomatis (`backup:run`) ke storage terpisah (S3-compatible)
- [ ] Test formulir kontak, cek jangkauan, dan lamaran kerja end-to-end
- [ ] Submit sitemap ke Google Search Console (`https://rikoardianto.web.id/sitemap.xml`)

## Troubleshooting Umum

| Masalah | Kemungkinan Penyebab | Solusi |
|---|---|---|
| 502 Bad Gateway | PHP-FPM belum jalan / socket path salah | `systemctl status php8.3-fpm`, cek path socket di Nginx & pool config sama |
| Asset CSS/JS tidak muncul | Belum `npm run build` atau `public/build` tidak ter-generate | Jalankan `npm run build`, cek folder `public/build` ada |
| Queue tidak jalan (email/notifikasi tidak terkirim) | Horizon belum aktif | `supervisorctl status`, restart `ptjts-horizon` |
| Error koneksi database | Kredensial Supabase salah / pooler port salah | Gunakan port `6543` (pooler) untuk runtime, `5432` untuk migration |
| `nginx -t` gagal "cannot load certificate" | Deploy config SSL sebelum sertifikat certbot ada | Ikuti urutan 3 tahap di bagian "Konfigurasi Nginx" — jangan pakai `ptjts.id.ssl.conf` sebelum certbot berhasil |
| Halaman admin 403 terus | Role user belum di-assign atau RLS Supabase memblokir | Cek `role_id` user di tabel `users`, cek RLS policy terkait |
