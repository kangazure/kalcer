# PT JTS — Rencana Eksekusi Project

> CATATAN AUDIT: ditemukan file app/Models (34 file) dan app/Providers (8 file)
> dari sesi kerja sebelumnya, sudah konsisten dengan desain Supabase schema.
> Provider-provider tersebut mendefinisikan KONTRAK yang harus dipenuhi:
> - App\Repositories\Contracts\* + App\Repositories\Eloquent\* (9 modul)
> - App\Events\* (5 event) + App\Listeners\* (6 listener)
> - App\Services\Supabase\* (SupabaseClient, SupabaseAuthService, SupabaseStorageService)
> - App\Http\Middleware\* (EnsureUserHasRole, LogActivityMiddleware, SecurityHeaders, TrackVisitor, VerifyCaptcha)
> - App\Exceptions\NetworkMonitorException
> - App\Facades\Supabase
> Melanjutkan dari sini, BUKAN mengulang dari nol.

## Fase 0 — Scaffolding & Database (SEDANG BERJALAN)
- [x] Struktur folder
- [ ] composer.json, package.json, vite.config.ts, tailwind.config.js
- [ ] .env.example, config/*.php
- [ ] Supabase SQL migration lengkap (35 tabel: users, roles, permissions, posts,
      categories, tags, comments, media, gallery, portfolio, team, services,
      packages, career, job_application, contact, faq, testimonial, subscriber,
      banner, slider, popup, announcement, notification, settings, analytics,
      visitor, activity_logs, network_monitor, coverage_area, maintenance,
      trouble_report, role_user, permission_role, post_tag)
- [ ] RLS Policy per tabel
- [ ] Trigger (updated_at, view counter, activity log, slug generator)
- [ ] Function (search, related posts, bandwidth aggregation)
- [ ] View (published_posts, active_packages, network_status_summary)
- [ ] Storage bucket + policy
- [ ] Laravel migration (mirror schema, untuk artisan tooling & seeding)

## ⚠️ INSIDEN: SANDBOX RESET (dicatat demi transparansi)
Sandbox sempat ter-reset di tengah sesi. Root cause: kemungkinan container
di-recycle setelah durasi kerja sangat panjang tanpa checkpoint. Dampak:
seluruh routes/, database/migrations, database/seeders, dan ~40 file Blade
views (layouts, partials, pages publik, sebagian admin) HILANG dan harus
dibangun ulang. File yang selamat: checkpoint zip pertama (141 file: SQL
migration, config, app/ dari Fase 0-1 awal) + 17 file admin view yang
dibuat setelah reset terjadi (tergabung otomatis saat restore).

MITIGASI KE DEPAN: checkpoint zip setiap ~30-40 file baru selesai, JANGAN
menunggu sampai modul besar selesai total. Update PROJECT_PLAN.md setiap
checkpoint agar status selalu bisa direkonstruksi meski sandbox reset lagi.

## STATUS SETELAH RESTORE (akurat per saat ini):
- app/ : 92 file (models, sebagian providers/repos/services/events dari checkpoint 1)
- supabase/ : 16 file SQL migration — LENGKAP, aman
- config/ : 16 file — LENGKAP, aman
- resources/views/admin/ : 17 file (survivor pasca-reset)
- resources/views/emails/ : 3 file — aman
- routes/ : KOSONG — perlu dibangun ulang TOTAL
- database/ : KOSONG — perlu dibangun ulang TOTAL
- resources/views/layouts, pages, partials, components : KOSONG — perlu dibangun ulang TOTAL
- resources/js, resources/scss : KOSONG — perlu dibangun ulang TOTAL



> Route names yang SUDAH dirujuk di kode (Mail/Listener) dan WAJIB ada saat
> routes dibuat: admin.career.applications, admin.contact.show,
> admin.network-monitor.show, admin.trouble-report.show, blog.show,
> sitemap.index, admin.contact.show

- [ ] Models + relationships (Eloquent, pgsql connection ke Supabase)
- [ ] Repositories + Services per modul
- [ ] Form Requests (validation)
- [ ] Middleware (role, rate limit, security headers)
- [ ] REST API Controllers + routes (resource lengkap, pagination/search/filter)
- [ ] Sanctum auth (login, register admin, token)
- [ ] Queue jobs (email, notifikasi)
- [ ] Scheduler (backup, report)
- [ ] Helpers

## Fase 2 — Frontend Publik (Blade)
- [ ] Layout master + partials (header, footer, loading screen, cursor, mouse glow)
- [ ] Home (hero, counter, partner, timeline, CTA, testimonial slider, coverage map, speedtest, status gangguan)
- [ ] Tentang Kami, Visi Misi, Sejarah
- [ ] Layanan (8 sub-halaman)
- [ ] Paket Internet
- [ ] Coverage Area + Cek Jangkauan
- [ ] Blog (listing, detail, kategori, tag, search) ala Medium
- [ ] Portfolio, Galeri, Testimoni
- [ ] Karir + form lamaran
- [ ] FAQ, Kontak, Privacy, Terms
- [ ] 404, Maintenance

## Fase 3 — Admin Dashboard
- [ ] Login + role-based middleware (5 role)
- [ ] Dashboard (statistik, chart.js)
- [ ] CRUD x17 modul
- [ ] Media manager
- [ ] Activity log, Analytics, Visitor
- [ ] Settings, Backup

## Fase 4 — ISP Feature Modules
- [ ] Network monitor (bandwidth/latency graph - Chart.js)
- [ ] Coverage area map (Leaflet/Google Maps)
- [ ] Speedtest widget
- [ ] Trouble report / status gangguan

## Fase 5 — Security, SEO, Integrasi, Deployment
- [ ] Security headers, CSRF, rate limit, captcha (Turnstile)
- [ ] SEO: sitemap, robots.txt, JSON-LD, OG/Twitter card
- [ ] Integrasi: WhatsApp, Telegram bot, SMTP, Google Maps
- [ ] Nginx config, Supervisor config, deployment guide
