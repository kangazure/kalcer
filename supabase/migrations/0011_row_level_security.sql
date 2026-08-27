-- =====================================================================
-- Migration: 0011_row_level_security.sql
-- Tujuan   : Mengaktifkan Row Level Security (RLS) pada SELURUH tabel
--            dan mendefinisikan policy akses berbasis role.
--
-- Prinsip keamanan yang dipakai:
--   1. Default DENY — RLS enabled berarti tanpa policy = tidak ada akses.
--   2. Tabel konten publik (posts published, services, packages, dst):
--      SELECT terbuka untuk anon & authenticated, tapi INSERT/UPDATE/
--      DELETE hanya untuk role tertentu (super_admin, admin, editor,
--      marketing sesuai modul).
--   3. Tabel sensitif (users, activity_logs, settings, contact, dsb):
--      hanya bisa diakses oleh role yang berwenang.
--   4. Tabel transaksional publik (contact, job_application, comments,
--      post_likes, post_bookmarks): INSERT terbuka untuk submission
--      dari publik, tapi SELECT/UPDATE/DELETE dibatasi.
--   5. service_role (dipakai backend Laravel via service key) selalu
--      bypass RLS secara native di Supabase — policy di bawah ini
--      mengatur akses dari sisi anon/authenticated (browser/PostgREST).
-- =====================================================================

-- ---------------------------------------------------------------------
-- 1. ROLES & PERMISSIONS — hanya admin yang boleh baca/tulis
-- ---------------------------------------------------------------------
alter table public.roles enable row level security;
alter table public.permissions enable row level security;
alter table public.permission_role enable row level security;
alter table public.role_user enable row level security;

create policy "roles_select_admin" on public.roles
    for select to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin'));

create policy "roles_write_super_admin" on public.roles
    for all to authenticated
    using (current_app_user_role_slug() = 'super_admin')
    with check (current_app_user_role_slug() = 'super_admin');

create policy "permissions_select_admin" on public.permissions
    for select to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin'));

create policy "permissions_write_super_admin" on public.permissions
    for all to authenticated
    using (current_app_user_role_slug() = 'super_admin')
    with check (current_app_user_role_slug() = 'super_admin');

create policy "permission_role_select_admin" on public.permission_role
    for select to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin'));

create policy "permission_role_write_super_admin" on public.permission_role
    for all to authenticated
    using (current_app_user_role_slug() = 'super_admin')
    with check (current_app_user_role_slug() = 'super_admin');

create policy "role_user_select_admin" on public.role_user
    for select to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin'));

create policy "role_user_write_super_admin" on public.role_user
    for all to authenticated
    using (current_app_user_role_slug() = 'super_admin')
    with check (current_app_user_role_slug() = 'super_admin');

-- ---------------------------------------------------------------------
-- 2. USERS — user hanya bisa lihat/ubah profil sendiri; admin lihat semua
-- ---------------------------------------------------------------------
alter table public.users enable row level security;

create policy "users_select_own_or_admin" on public.users
    for select to authenticated
    using (
        auth_user_id = auth.uid()
        or current_app_user_role_slug() in ('super_admin', 'admin')
    );

create policy "users_update_own_profile" on public.users
    for update to authenticated
    using (auth_user_id = auth.uid())
    with check (auth_user_id = auth.uid());

create policy "users_admin_manage" on public.users
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin'));

-- Tim (public profile) tetap bisa dibaca publik lewat tabel `team`, jadi
-- tabel `users` murni internal dan TIDAK dibuka ke anon.

-- ---------------------------------------------------------------------
-- 3. BLOG MODULE — categories, tags, posts: SELECT publik untuk published
-- ---------------------------------------------------------------------
alter table public.categories enable row level security;
alter table public.tags enable row level security;
alter table public.posts enable row level security;
alter table public.post_tag enable row level security;
alter table public.comments enable row level security;
alter table public.post_likes enable row level security;
alter table public.post_bookmarks enable row level security;

create policy "categories_select_public" on public.categories
    for select to anon, authenticated
    using (is_active = true);

create policy "categories_write_editor" on public.categories
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'editor'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'editor'));

create policy "tags_select_public" on public.tags
    for select to anon, authenticated
    using (true);

create policy "tags_write_editor" on public.tags
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'editor'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'editor'));

create policy "posts_select_published_public" on public.posts
    for select to anon, authenticated
    using (status = 'published' and deleted_at is null);

create policy "posts_select_own_drafts" on public.posts
    for select to authenticated
    using (author_id = current_app_user_id());

create policy "posts_select_admin_all" on public.posts
    for select to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin'));

create policy "posts_write_editor" on public.posts
    for insert to authenticated
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'editor', 'marketing'));

create policy "posts_update_own_or_admin" on public.posts
    for update to authenticated
    using (
        author_id = current_app_user_id()
        or current_app_user_role_slug() in ('super_admin', 'admin')
    )
    with check (
        author_id = current_app_user_id()
        or current_app_user_role_slug() in ('super_admin', 'admin')
    );

create policy "posts_delete_admin" on public.posts
    for delete to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin'));

create policy "post_tag_select_public" on public.post_tag
    for select to anon, authenticated
    using (true);

create policy "post_tag_write_editor" on public.post_tag
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'editor'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'editor'));

-- Comments: publik boleh INSERT (guest comment, masuk status pending),
-- tapi SELECT hanya yang approved untuk publik; admin/editor lihat semua.
create policy "comments_select_approved_public" on public.comments
    for select to anon, authenticated
    using (status = 'approved');

create policy "comments_select_admin_all" on public.comments
    for select to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'editor'));

create policy "comments_insert_public" on public.comments
    for insert to anon, authenticated
    with check (status = 'pending'); -- selalu masuk sebagai pending, anti-spam moderasi

create policy "comments_moderate_editor" on public.comments
    for update to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'editor'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'editor'));

create policy "comments_delete_admin" on public.comments
    for delete to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin'));

-- post_likes: publik bisa like (insert) & unlike (delete) milik sendiri
create policy "post_likes_select_public" on public.post_likes
    for select to anon, authenticated
    using (true);

create policy "post_likes_insert_public" on public.post_likes
    for insert to anon, authenticated
    with check (true);

create policy "post_likes_delete_own" on public.post_likes
    for delete to authenticated
    using (user_id = current_app_user_id());

-- post_bookmarks: hanya user login, milik sendiri
create policy "post_bookmarks_own" on public.post_bookmarks
    for all to authenticated
    using (user_id = current_app_user_id())
    with check (user_id = current_app_user_id());

-- ---------------------------------------------------------------------
-- 4. MEDIA, GALLERY, PORTFOLIO, TEAM — SELECT publik, write terbatas
-- ---------------------------------------------------------------------
alter table public.media enable row level security;
alter table public.gallery enable row level security;
alter table public.portfolio enable row level security;
alter table public.team enable row level security;

create policy "media_select_public" on public.media
    for select to anon, authenticated
    using (deleted_at is null);

create policy "media_write_editor" on public.media
    for insert to authenticated
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'editor', 'marketing'));

create policy "media_update_own_or_admin" on public.media
    for update to authenticated
    using (uploader_id = current_app_user_id() or current_app_user_role_slug() in ('super_admin', 'admin'))
    with check (uploader_id = current_app_user_id() or current_app_user_role_slug() in ('super_admin', 'admin'));

create policy "media_delete_admin" on public.media
    for delete to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin'));

create policy "gallery_select_published" on public.gallery
    for select to anon, authenticated
    using (is_published = true);

create policy "gallery_write_editor" on public.gallery
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'editor', 'marketing'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'editor', 'marketing'));

create policy "portfolio_select_published" on public.portfolio
    for select to anon, authenticated
    using (is_published = true);

create policy "portfolio_write_editor" on public.portfolio
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'editor', 'marketing'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'editor', 'marketing'));

create policy "team_select_active" on public.team
    for select to anon, authenticated
    using (is_active = true);

create policy "team_write_admin" on public.team
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin'));

-- ---------------------------------------------------------------------
-- 5. SERVICES & PACKAGES — SELECT publik penuh, write admin/marketing
-- ---------------------------------------------------------------------
alter table public.services enable row level security;
alter table public.packages enable row level security;

create policy "services_select_active" on public.services
    for select to anon, authenticated
    using (is_active = true);

create policy "services_write_admin" on public.services
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing'));

create policy "packages_select_active" on public.packages
    for select to anon, authenticated
    using (is_active = true);

create policy "packages_write_admin" on public.packages
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing'));

-- ---------------------------------------------------------------------
-- 6. CAREER & JOB APPLICATION — lowongan publik, lamaran insert publik
-- ---------------------------------------------------------------------
alter table public.career enable row level security;
alter table public.job_application enable row level security;

create policy "career_select_active" on public.career
    for select to anon, authenticated
    using (is_active = true);

create policy "career_select_admin_all" on public.career
    for select to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin'));

create policy "career_write_admin" on public.career
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin'));

create policy "job_application_insert_public" on public.job_application
    for insert to anon, authenticated
    with check (true);

create policy "job_application_select_admin" on public.job_application
    for select to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin'));

create policy "job_application_update_admin" on public.job_application
    for update to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin'));

-- ---------------------------------------------------------------------
-- 7. CONTACT, FAQ, TESTIMONIAL, SUBSCRIBER
-- ---------------------------------------------------------------------
alter table public.contact enable row level security;
alter table public.faq enable row level security;
alter table public.testimonial enable row level security;
alter table public.subscriber enable row level security;

create policy "contact_insert_public" on public.contact
    for insert to anon, authenticated
    with check (true);

create policy "contact_select_admin" on public.contact
    for select to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing', 'operator'));

create policy "contact_update_admin" on public.contact
    for update to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing', 'operator'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing', 'operator'));

create policy "faq_select_active" on public.faq
    for select to anon, authenticated
    using (is_active = true);

create policy "faq_write_admin" on public.faq
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'editor'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'editor'));

create policy "testimonial_select_published" on public.testimonial
    for select to anon, authenticated
    using (is_published = true);

create policy "testimonial_write_admin" on public.testimonial
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing'));

create policy "subscriber_insert_public" on public.subscriber
    for insert to anon, authenticated
    with check (true);

create policy "subscriber_select_admin" on public.subscriber
    for select to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing'));

create policy "subscriber_delete_self_or_admin" on public.subscriber
    for delete to anon, authenticated
    using (true); -- unsubscribe via token divalidasi di application layer, bukan RLS

-- ---------------------------------------------------------------------
-- 8. PROMO CONTENT — banner, slider, popup, announcement
-- ---------------------------------------------------------------------
alter table public.banner enable row level security;
alter table public.slider enable row level security;
alter table public.popup enable row level security;
alter table public.announcement enable row level security;

create policy "banner_select_active" on public.banner
    for select to anon, authenticated
    using (is_active = true);

create policy "banner_write_marketing" on public.banner
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing'));

create policy "slider_select_active" on public.slider
    for select to anon, authenticated
    using (is_active = true);

create policy "slider_write_marketing" on public.slider
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing'));

create policy "popup_select_active" on public.popup
    for select to anon, authenticated
    using (is_active = true);

create policy "popup_write_marketing" on public.popup
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing'));

create policy "announcement_select_active" on public.announcement
    for select to anon, authenticated
    using (is_active = true);

create policy "announcement_write_admin" on public.announcement
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'operator'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'operator'));

-- ---------------------------------------------------------------------
-- 9. SYSTEM TABLES — notification, settings, analytics, visitor, activity_logs
-- ---------------------------------------------------------------------
alter table public.notification enable row level security;
alter table public.settings enable row level security;
alter table public.visitor enable row level security;
alter table public.analytics enable row level security;
alter table public.activity_logs enable row level security;

create policy "notification_own" on public.notification
    for select to authenticated
    using (user_id = current_app_user_id());

create policy "notification_update_own" on public.notification
    for update to authenticated
    using (user_id = current_app_user_id())
    with check (user_id = current_app_user_id());

create policy "settings_select_public_flagged" on public.settings
    for select to anon, authenticated
    using (is_public = true);

create policy "settings_select_admin_all" on public.settings
    for select to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin'));

create policy "settings_write_super_admin" on public.settings
    for all to authenticated
    using (current_app_user_role_slug() = 'super_admin')
    with check (current_app_user_role_slug() = 'super_admin');

create policy "visitor_insert_public" on public.visitor
    for insert to anon, authenticated
    with check (true);

create policy "visitor_select_admin" on public.visitor
    for select to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing'));

create policy "analytics_select_admin" on public.analytics
    for select to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'marketing'));

create policy "activity_logs_select_admin" on public.activity_logs
    for select to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin'));

-- activity_logs tidak punya policy INSERT untuk anon/authenticated karena
-- pencatatan dilakukan via service_role (backend Laravel) yang bypass RLS.

-- ---------------------------------------------------------------------
-- 10. ISP NETWORK MODULES — coverage_area, network_monitor, maintenance,
--     trouble_report
-- ---------------------------------------------------------------------
alter table public.coverage_area enable row level security;
alter table public.network_monitor enable row level security;
alter table public.network_monitor_history enable row level security;
alter table public.maintenance enable row level security;
alter table public.trouble_report enable row level security;

create policy "coverage_area_select_active" on public.coverage_area
    for select to anon, authenticated
    using (is_active = true);

create policy "coverage_area_write_operator" on public.coverage_area
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'operator'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'operator'));

-- network_monitor: status ringkas boleh dibaca publik (transparansi status
-- jaringan), tapi field sensitif (ip_address) tetap ada di tabel — untuk
-- publik sebaiknya konsumsi via view network_status_summary, bukan tabel
-- langsung. Policy ini tetap membuka SELECT supaya dashboard internal
-- berjalan; endpoint publik diarahkan memakai view yang sudah disaring.
create policy "network_monitor_select_authenticated" on public.network_monitor
    for select to authenticated
    using (true);

create policy "network_monitor_write_operator" on public.network_monitor
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'operator'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'operator'));

create policy "network_monitor_history_select_authenticated" on public.network_monitor_history
    for select to authenticated
    using (true);

create policy "network_monitor_history_insert_operator" on public.network_monitor_history
    for insert to authenticated
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'operator'));

create policy "maintenance_select_public" on public.maintenance
    for select to anon, authenticated
    using (status in ('scheduled', 'ongoing', 'completed'));

create policy "maintenance_write_operator" on public.maintenance
    for all to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'operator'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'operator'));

create policy "trouble_report_insert_public" on public.trouble_report
    for insert to anon, authenticated
    with check (true);

create policy "trouble_report_select_admin" on public.trouble_report
    for select to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'operator'));

create policy "trouble_report_update_operator" on public.trouble_report
    for update to authenticated
    using (current_app_user_role_slug() in ('super_admin', 'admin', 'operator'))
    with check (current_app_user_role_slug() in ('super_admin', 'admin', 'operator'));
