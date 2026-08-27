-- =====================================================================
-- Migration: 0005_services_and_packages.sql
-- Tujuan   : Modul layanan ISP (Internet Rumah, Bisnis, Dedicated, Metro
--            Ethernet, Fiber Optik, Cloud, Data Center, Colocation,
--            Managed Service) dan paket internet dengan harga.
-- =====================================================================

-- ---------------------------------------------------------------------
-- TABLE: services — 8 layanan utama JTS
-- ---------------------------------------------------------------------
create table if not exists public.services (
    id                  bigint generated always as identity primary key,
    uuid                uuid not null default gen_random_uuid(),
    name                varchar(150) not null,
    slug                varchar(170) not null,
    icon                varchar(60),
    short_description   varchar(300),
    description         text,
    features            jsonb not null default '[]'::jsonb,  -- array of strings
    benefits            jsonb not null default '[]'::jsonb,
    cover_image_url     text,
    icon_image_url      text,
    sort_order          integer not null default 0,
    is_active           boolean not null default true,
    is_featured_home    boolean not null default false,
    seo_title           varchar(160),
    seo_description     varchar(320),
    created_at          timestamptz not null default timezone('utc', now()),
    updated_at          timestamptz not null default timezone('utc', now()),

    constraint uq_services_slug unique (slug)
);
comment on table public.services is 'Daftar layanan ISP: Internet Rumah, Internet Bisnis, Dedicated Internet, Metro Ethernet, Fiber Optik, Cloud, Data Center, Colocation, Managed Service.';

create index if not exists idx_services_slug on public.services (slug);
create index if not exists idx_services_active on public.services (is_active);
create index if not exists idx_services_featured on public.services (is_featured_home);

create trigger trg_services_updated_at
    before update on public.services
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: packages — paket internet dengan harga & speed
-- ---------------------------------------------------------------------
create table if not exists public.packages (
    id                  bigint generated always as identity primary key,
    uuid                uuid not null default gen_random_uuid(),
    service_id          bigint references public.services (id) on delete set null,
    category            package_category not null default 'home',
    name                varchar(120) not null,
    slug                varchar(140) not null,
    speed_mbps_download integer not null,
    speed_mbps_upload   integer not null,
    price               numeric(12, 2) not null,
    price_promo         numeric(12, 2),
    billing_cycle       package_billing_cycle not null default 'monthly',
    is_unlimited        boolean not null default true,
    fup_gb              integer,                       -- fair usage policy, jika ada
    installation_fee    numeric(12, 2) not null default 0,
    features            jsonb not null default '[]'::jsonb,
    is_popular          boolean not null default false,
    is_active           boolean not null default true,
    sort_order          integer not null default 0,
    created_at          timestamptz not null default timezone('utc', now()),
    updated_at          timestamptz not null default timezone('utc', now()),

    constraint uq_packages_slug unique (slug),
    constraint chk_packages_price_positive check (price >= 0),
    constraint chk_packages_speed_positive check (speed_mbps_download > 0 and speed_mbps_upload > 0)
);
comment on table public.packages is 'Paket internet (harga, kecepatan, kategori) yang ditawarkan ke pelanggan rumah/bisnis.';

create index if not exists idx_packages_service on public.packages (service_id);
create index if not exists idx_packages_category on public.packages (category);
create index if not exists idx_packages_active on public.packages (is_active);
create index if not exists idx_packages_popular on public.packages (is_popular);

create trigger trg_packages_updated_at
    before update on public.packages
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- VIEW: active_packages — hanya paket aktif, terurut sesuai sort_order
-- ---------------------------------------------------------------------
create or replace view public.active_packages as
select pkg.*, s.name as service_name, s.slug as service_slug
from public.packages pkg
left join public.services s on s.id = pkg.service_id
where pkg.is_active = true
order by pkg.category, pkg.sort_order, pkg.speed_mbps_download;

comment on view public.active_packages is 'Paket internet aktif lengkap dengan info layanan terkait, siap ditampilkan di halaman publik.';
