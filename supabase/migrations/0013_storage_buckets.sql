-- =====================================================================
-- Migration: 0013_storage_buckets.sql
-- Tujuan   : Membuat Supabase Storage bucket & policy akses file untuk
--            media, gallery, dokumen (CV pelamar), dan avatar pengguna.
-- =====================================================================

-- ---------------------------------------------------------------------
-- BUCKET: media (publik) — gambar artikel blog, banner, slider, services
-- ---------------------------------------------------------------------
insert into storage.buckets (id, name, public, file_size_limit, allowed_mime_types)
values (
    'media',
    'media',
    true,
    10485760, -- 10MB
    array['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'image/gif']
)
on conflict (id) do nothing;

-- ---------------------------------------------------------------------
-- BUCKET: gallery (publik) — foto galeri kegiatan
-- ---------------------------------------------------------------------
insert into storage.buckets (id, name, public, file_size_limit, allowed_mime_types)
values (
    'gallery',
    'gallery',
    true,
    10485760,
    array['image/jpeg', 'image/png', 'image/webp']
)
on conflict (id) do nothing;

-- ---------------------------------------------------------------------
-- BUCKET: documents (privat) — CV/lampiran lamaran kerja, dokumen legal
-- ---------------------------------------------------------------------
insert into storage.buckets (id, name, public, file_size_limit, allowed_mime_types)
values (
    'documents',
    'documents',
    false,
    5242880, -- 5MB
    array['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
)
on conflict (id) do nothing;

-- ---------------------------------------------------------------------
-- BUCKET: avatars (publik) — foto profil tim/user
-- ---------------------------------------------------------------------
insert into storage.buckets (id, name, public, file_size_limit, allowed_mime_types)
values (
    'avatars',
    'avatars',
    true,
    2097152, -- 2MB
    array['image/jpeg', 'image/png', 'image/webp']
)
on conflict (id) do nothing;

-- ---------------------------------------------------------------------
-- STORAGE POLICY: media & gallery — publik bisa baca, hanya staff upload
-- ---------------------------------------------------------------------
create policy "media_public_read"
    on storage.objects for select
    using (bucket_id = 'media');

create policy "media_staff_upload"
    on storage.objects for insert
    to authenticated
    with check (
        bucket_id = 'media'
        and public.current_app_user_role_slug() in ('super_admin', 'admin', 'editor', 'marketing')
    );

create policy "media_staff_update"
    on storage.objects for update
    to authenticated
    using (
        bucket_id = 'media'
        and public.current_app_user_role_slug() in ('super_admin', 'admin', 'editor', 'marketing')
    );

create policy "media_admin_delete"
    on storage.objects for delete
    to authenticated
    using (
        bucket_id = 'media'
        and public.current_app_user_role_slug() in ('super_admin', 'admin')
    );

create policy "gallery_public_read"
    on storage.objects for select
    using (bucket_id = 'gallery');

create policy "gallery_staff_upload"
    on storage.objects for insert
    to authenticated
    with check (
        bucket_id = 'gallery'
        and public.current_app_user_role_slug() in ('super_admin', 'admin', 'editor', 'marketing')
    );

create policy "gallery_admin_delete"
    on storage.objects for delete
    to authenticated
    using (
        bucket_id = 'gallery'
        and public.current_app_user_role_slug() in ('super_admin', 'admin')
    );

-- ---------------------------------------------------------------------
-- STORAGE POLICY: documents — privat, hanya pemilik (pelamar via service
-- key saat submit) dan admin/HR yang bisa baca; tidak ada akses publik.
-- ---------------------------------------------------------------------
create policy "documents_insert_public"
    on storage.objects for insert
    to anon, authenticated
    with check (bucket_id = 'documents');

create policy "documents_admin_read"
    on storage.objects for select
    to authenticated
    using (
        bucket_id = 'documents'
        and public.current_app_user_role_slug() in ('super_admin', 'admin')
    );

create policy "documents_admin_delete"
    on storage.objects for delete
    to authenticated
    using (
        bucket_id = 'documents'
        and public.current_app_user_role_slug() in ('super_admin', 'admin')
    );

-- ---------------------------------------------------------------------
-- STORAGE POLICY: avatars — publik baca, user hanya bisa upload/update
-- avatar miliknya sendiri (folder path = auth_user_id)
-- ---------------------------------------------------------------------
create policy "avatars_public_read"
    on storage.objects for select
    using (bucket_id = 'avatars');

create policy "avatars_owner_upload"
    on storage.objects for insert
    to authenticated
    with check (
        bucket_id = 'avatars'
        and (storage.foldername(name))[1] = auth.uid()::text
    );

create policy "avatars_owner_update"
    on storage.objects for update
    to authenticated
    using (
        bucket_id = 'avatars'
        and (storage.foldername(name))[1] = auth.uid()::text
    );

create policy "avatars_owner_delete"
    on storage.objects for delete
    to authenticated
    using (
        bucket_id = 'avatars'
        and (storage.foldername(name))[1] = auth.uid()::text
    );
