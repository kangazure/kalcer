-- =====================================================================
-- PT JARINGAN TEKNOLOGI SEJAHTERA (JTS) — SUPABASE DATABASE SCHEMA
-- Migration: 0001_extensions_and_types.sql
-- Tujuan   : Mengaktifkan extension PostgreSQL & mendefinisikan custom
--            ENUM types serta fungsi utilitas yang dipakai berulang
--            oleh seluruh tabel pada migration berikutnya.
-- =====================================================================

-- ---------------------------------------------------------------------
-- 1. EXTENSIONS
-- ---------------------------------------------------------------------
create extension if not exists "pgcrypto";       -- gen_random_uuid(), hashing
create extension if not exists "pg_trgm";         -- fuzzy text search (ILIKE index)
create extension if not exists "unaccent";        -- normalisasi pencarian teks
create extension if not exists "citext";          -- case-insensitive text (email)

-- ---------------------------------------------------------------------
-- 2. CUSTOM ENUM TYPES
-- ---------------------------------------------------------------------
do $$
begin
    if not exists (select 1 from pg_type where typname = 'user_status') then
        create type user_status as enum ('active', 'inactive', 'suspended', 'pending');
    end if;

    if not exists (select 1 from pg_type where typname = 'content_status') then
        create type content_status as enum ('draft', 'review', 'published', 'archived', 'scheduled');
    end if;

    if not exists (select 1 from pg_type where typname = 'comment_status') then
        create type comment_status as enum ('pending', 'approved', 'spam', 'rejected');
    end if;

    if not exists (select 1 from pg_type where typname = 'media_type') then
        create type media_type as enum ('image', 'video', 'document', 'audio', 'other');
    end if;

    if not exists (select 1 from pg_type where typname = 'package_billing_cycle') then
        create type package_billing_cycle as enum ('monthly', 'quarterly', 'semiannual', 'annual');
    end if;

    if not exists (select 1 from pg_type where typname = 'package_category') then
        create type package_category as enum ('home', 'business', 'dedicated', 'metro_ethernet', 'enterprise');
    end if;

    if not exists (select 1 from pg_type where typname = 'job_type') then
        create type job_type as enum ('full_time', 'part_time', 'internship', 'contract', 'remote');
    end if;

    if not exists (select 1 from pg_type where typname = 'application_status') then
        create type application_status as enum ('submitted', 'screening', 'interview', 'offered', 'hired', 'rejected');
    end if;

    if not exists (select 1 from pg_type where typname = 'contact_status') then
        create type contact_status as enum ('new', 'in_progress', 'resolved', 'closed', 'spam');
    end if;

    if not exists (select 1 from pg_type where typname = 'notification_channel') then
        create type notification_channel as enum ('database', 'email', 'whatsapp', 'telegram', 'push');
    end if;

    if not exists (select 1 from pg_type where typname = 'monitor_status') then
        create type monitor_status as enum ('online', 'offline', 'degraded', 'maintenance', 'unknown');
    end if;

    if not exists (select 1 from pg_type where typname = 'maintenance_status') then
        create type maintenance_status as enum ('scheduled', 'ongoing', 'completed', 'cancelled');
    end if;

    if not exists (select 1 from pg_type where typname = 'trouble_severity') then
        create type trouble_severity as enum ('low', 'medium', 'high', 'critical');
    end if;

    if not exists (select 1 from pg_type where typname = 'trouble_status') then
        create type trouble_status as enum ('open', 'investigating', 'resolved', 'closed');
    end if;

    if not exists (select 1 from pg_type where typname = 'banner_position') then
        create type banner_position as enum ('home_hero', 'sidebar', 'popup', 'top_bar', 'footer');
    end if;
end$$;

-- ---------------------------------------------------------------------
-- 3. UTILITY FUNCTIONS (dipakai trigger di migration berikutnya)
-- ---------------------------------------------------------------------

-- Auto-update kolom updated_at setiap kali row di-UPDATE.
create or replace function set_updated_at()
returns trigger
language plpgsql
as $$
begin
    new.updated_at = timezone('utc', now());
    return new;
end;
$$;

-- Generate slug dari string judul (lowercase, strip karakter non-alfanumerik).
create or replace function generate_slug(input_text text)
returns text
language plpgsql
immutable
as $$
declare
    slug text;
begin
    slug := lower(unaccent(trim(input_text)));
    slug := regexp_replace(slug, '[^a-z0-9\s-]', '', 'g');
    slug := regexp_replace(slug, '[\s_-]+', '-', 'g');
    slug := trim(slug, '-');
    return slug;
end;
$$;

-- Hitung estimasi waktu baca artikel (asumsi 200 kata/menit).
create or replace function calculate_reading_time(content text)
returns integer
language plpgsql
immutable
as $$
declare
    word_count integer;
begin
    word_count := array_length(regexp_split_to_array(trim(content), '\s+'), 1);
    return greatest(1, ceil(word_count::numeric / 200));
end;
$$;

comment on function set_updated_at() is 'Trigger function: auto-update kolom updated_at pada setiap UPDATE row.';
comment on function generate_slug(text) is 'Utility: konversi string menjadi slug URL-friendly.';
comment on function calculate_reading_time(text) is 'Utility: estimasi waktu baca artikel berdasarkan jumlah kata.';
