-- =====================================================================
-- Migration: 0004_media_gallery_portfolio_team.sql
-- Tujuan   : Media manager, galeri, portfolio proyek, dan tim perusahaan.
-- =====================================================================

-- ---------------------------------------------------------------------
-- TABLE: media — pusat manajemen file upload (terhubung Supabase Storage)
-- ---------------------------------------------------------------------
create table if not exists public.media (
    id                  bigint generated always as identity primary key,
    uuid                uuid not null default gen_random_uuid(),
    uploader_id         bigint references public.users (id) on delete set null,
    bucket              varchar(50) not null default 'media',
    storage_path        text not null,               -- path relatif di bucket Supabase Storage
    public_url          text,
    file_name           varchar(255) not null,
    original_name       varchar(255) not null,
    mime_type           varchar(120) not null,
    type                media_type not null default 'image',
    size_bytes          bigint not null default 0,
    width               integer,
    height              integer,
    duration_seconds     integer,                      -- untuk video/audio
    alt_text            varchar(255),
    caption             text,
    collection_name     varchar(80) not null default 'default', -- ala spatie/medialibrary
    model_type          varchar(100),                 -- polymorphic: 'Post', 'Gallery', dst
    model_id             bigint,
    sort_order          integer not null default 0,
    deleted_at          timestamptz,
    created_at          timestamptz not null default timezone('utc', now()),
    updated_at          timestamptz not null default timezone('utc', now()),

    constraint uq_media_uuid unique (uuid)
);
comment on table public.media is 'Media manager terpusat, menyimpan metadata file yang fisiknya berada di Supabase Storage bucket.';

create index if not exists idx_media_uploader on public.media (uploader_id);
create index if not exists idx_media_model on public.media (model_type, model_id);
create index if not exists idx_media_type on public.media (type);
create index if not exists idx_media_deleted_at on public.media (deleted_at);

create trigger trg_media_updated_at
    before update on public.media
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: gallery — album/galeri foto kegiatan perusahaan
-- ---------------------------------------------------------------------
create table if not exists public.gallery (
    id              bigint generated always as identity primary key,
    uuid            uuid not null default gen_random_uuid(),
    title           varchar(180) not null,
    slug            varchar(200) not null,
    description     text,
    cover_image_url text,
    category        varchar(60),                      -- contoh: 'Kegiatan', 'Instalasi', 'Event'
    is_published    boolean not null default true,
    sort_order      integer not null default 0,
    created_by      bigint references public.users (id) on delete set null,
    created_at      timestamptz not null default timezone('utc', now()),
    updated_at      timestamptz not null default timezone('utc', now()),

    constraint uq_gallery_slug unique (slug)
);
comment on table public.gallery is 'Album galeri foto kegiatan perusahaan; gambar individual disimpan di tabel media (model_type=Gallery).';

create index if not exists idx_gallery_slug on public.gallery (slug);
create index if not exists idx_gallery_published on public.gallery (is_published);

create trigger trg_gallery_updated_at
    before update on public.gallery
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: portfolio — studi kasus / proyek yang sudah dikerjakan JTS
-- ---------------------------------------------------------------------
create table if not exists public.portfolio (
    id                  bigint generated always as identity primary key,
    uuid                uuid not null default gen_random_uuid(),
    title               varchar(200) not null,
    slug                varchar(220) not null,
    client_name         varchar(150),
    category            varchar(60),                  -- 'Internet Bisnis', 'Dedicated', 'Metro Ethernet', dst
    location            varchar(150),
    summary             varchar(500),
    description         text,
    cover_image_url     text,
    result_metric_label varchar(100),                  -- contoh: "Peningkatan Uptime"
    result_metric_value varchar(50),                   -- contoh: "99.9%"
    project_year        smallint,
    is_featured         boolean not null default false,
    is_published        boolean not null default true,
    sort_order          integer not null default 0,
    seo_title           varchar(160),
    seo_description     varchar(320),
    created_by          bigint references public.users (id) on delete set null,
    created_at          timestamptz not null default timezone('utc', now()),
    updated_at          timestamptz not null default timezone('utc', now()),

    constraint uq_portfolio_slug unique (slug)
);
comment on table public.portfolio is 'Studi kasus / portfolio proyek yang telah dikerjakan oleh JTS untuk klien.';

create index if not exists idx_portfolio_slug on public.portfolio (slug);
create index if not exists idx_portfolio_featured on public.portfolio (is_featured);
create index if not exists idx_portfolio_published on public.portfolio (is_published);
create index if not exists idx_portfolio_category on public.portfolio (category);

create trigger trg_portfolio_updated_at
    before update on public.portfolio
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: team — susunan struktur organisasi yang ditampilkan di "Tim Kami"
-- ---------------------------------------------------------------------
create table if not exists public.team (
    id              bigint generated always as identity primary key,
    uuid            uuid not null default gen_random_uuid(),
    user_id         bigint references public.users (id) on delete set null,
    name            varchar(150) not null,
    position        varchar(100) not null,
    department      varchar(100),
    photo_url       text,
    bio             text,
    linkedin_url    text,
    email           citext,
    sort_order      integer not null default 0,
    is_management   boolean not null default false,    -- jajaran direksi/komisaris
    is_active       boolean not null default true,
    created_at      timestamptz not null default timezone('utc', now()),
    updated_at      timestamptz not null default timezone('utc', now()),

    constraint uq_team_uuid unique (uuid)
);
comment on table public.team is 'Susunan tim/struktur organisasi perusahaan untuk halaman Tentang Kami.';

create index if not exists idx_team_sort on public.team (sort_order);
create index if not exists idx_team_active on public.team (is_active);
create index if not exists idx_team_management on public.team (is_management);
