-- =====================================================================
-- Migration: 0008_promo_content.sql
-- Tujuan   : Modul konten promosi — banner, slider, popup, announcement.
-- =====================================================================

-- ---------------------------------------------------------------------
-- TABLE: banner — banner statis per posisi (home_hero, sidebar, top_bar, dst)
-- ---------------------------------------------------------------------
create table if not exists public.banner (
    id              bigint generated always as identity primary key,
    uuid            uuid not null default gen_random_uuid(),
    title           varchar(180) not null,
    position        banner_position not null default 'home_hero',
    image_url       text not null,
    image_url_mobile text,
    link_url        text,
    link_target     varchar(10) not null default '_self',
    alt_text        varchar(255),
    starts_at       timestamptz,
    ends_at         timestamptz,
    is_active       boolean not null default true,
    sort_order      integer not null default 0,
    click_count     bigint not null default 0,
    impression_count bigint not null default 0,
    created_by      bigint references public.users (id) on delete set null,
    created_at      timestamptz not null default timezone('utc', now()),
    updated_at      timestamptz not null default timezone('utc', now()),

    constraint chk_banner_dates check (starts_at is null or ends_at is null or starts_at <= ends_at)
);
comment on table public.banner is 'Banner statis ditampilkan pada posisi tertentu di halaman publik.';

create index if not exists idx_banner_position on public.banner (position);
create index if not exists idx_banner_active on public.banner (is_active);

create trigger trg_banner_updated_at
    before update on public.banner
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: slider — slide hero carousel (Swiper.js) di halaman Home
-- ---------------------------------------------------------------------
create table if not exists public.slider (
    id              bigint generated always as identity primary key,
    uuid            uuid not null default gen_random_uuid(),
    title           varchar(180) not null,
    subtitle        varchar(300),
    description     text,
    image_url       text not null,
    video_url       text,                              -- opsional, background video
    cta_label       varchar(60),
    cta_url         text,
    sort_order      integer not null default 0,
    is_active       boolean not null default true,
    created_at      timestamptz not null default timezone('utc', now()),
    updated_at      timestamptz not null default timezone('utc', now()),

    constraint uq_slider_uuid unique (uuid)
);
comment on table public.slider is 'Slide hero carousel halaman Home menggunakan Swiper.js.';

create index if not exists idx_slider_active_sort on public.slider (is_active, sort_order);

create trigger trg_slider_updated_at
    before update on public.slider
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: popup — popup promosi/modal informasi
-- ---------------------------------------------------------------------
create table if not exists public.popup (
    id              bigint generated always as identity primary key,
    uuid            uuid not null default gen_random_uuid(),
    title           varchar(180) not null,
    content         text,
    image_url       text,
    link_url        text,
    link_label      varchar(60),
    display_rule    varchar(30) not null default 'once_per_session', -- once_per_session | every_visit | once_per_day
    show_delay_ms   integer not null default 2000,
    starts_at       timestamptz,
    ends_at         timestamptz,
    is_active       boolean not null default false,
    created_by      bigint references public.users (id) on delete set null,
    created_at      timestamptz not null default timezone('utc', now()),
    updated_at      timestamptz not null default timezone('utc', now()),

    constraint chk_popup_dates check (starts_at is null or ends_at is null or starts_at <= ends_at)
);
comment on table public.popup is 'Popup informasi/promosi yang muncul sesuai aturan tampil tertentu.';

create index if not exists idx_popup_active on public.popup (is_active);

create trigger trg_popup_updated_at
    before update on public.popup
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: announcement — pengumuman global (gangguan, maintenance, info penting)
-- ---------------------------------------------------------------------
create table if not exists public.announcement (
    id              bigint generated always as identity primary key,
    uuid            uuid not null default gen_random_uuid(),
    title           varchar(200) not null,
    content         text not null,
    severity        varchar(20) not null default 'info', -- info | warning | critical
    is_active       boolean not null default true,
    starts_at       timestamptz not null default timezone('utc', now()),
    ends_at         timestamptz,
    created_by      bigint references public.users (id) on delete set null,
    created_at      timestamptz not null default timezone('utc', now()),
    updated_at      timestamptz not null default timezone('utc', now()),

    constraint chk_announcement_severity check (severity in ('info', 'warning', 'critical'))
);
comment on table public.announcement is 'Pengumuman global ditampilkan sebagai top bar di seluruh halaman publik (misal info gangguan).';

create index if not exists idx_announcement_active on public.announcement (is_active);

create trigger trg_announcement_updated_at
    before update on public.announcement
    for each row execute function set_updated_at();
