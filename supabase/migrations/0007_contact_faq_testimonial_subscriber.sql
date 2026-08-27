-- =====================================================================
-- Migration: 0007_contact_faq_testimonial_subscriber.sql
-- Tujuan   : Modul kontak (form masuk), FAQ, testimoni pelanggan, dan
--            subscriber newsletter.
-- =====================================================================

-- ---------------------------------------------------------------------
-- TABLE: contact — pesan masuk dari form kontak / cek jangkauan
-- ---------------------------------------------------------------------
create table if not exists public.contact (
    id              bigint generated always as identity primary key,
    uuid            uuid not null default gen_random_uuid(),
    name            varchar(150) not null,
    email           citext not null,
    phone           varchar(20),
    subject         varchar(200),
    message         text not null,
    source          varchar(50) not null default 'contact_form', -- contact_form | coverage_check | whatsapp
    address         text,                              -- untuk cek jangkauan
    latitude        numeric(10, 6),
    longitude       numeric(10, 6),
    status          contact_status not null default 'new',
    assigned_to     bigint references public.users (id) on delete set null,
    handled_at      timestamptz,
    notes           text,
    ip_address      inet,
    user_agent      text,
    created_at      timestamptz not null default timezone('utc', now()),
    updated_at      timestamptz not null default timezone('utc', now()),

    constraint chk_contact_phone_format check (phone is null or phone ~ '^[0-9+\-\s()]{6,20}$')
);
comment on table public.contact is 'Pesan masuk dari form kontak dan permintaan cek jangkauan internet.';

create index if not exists idx_contact_status on public.contact (status);
create index if not exists idx_contact_source on public.contact (source);
create index if not exists idx_contact_assigned on public.contact (assigned_to);
create index if not exists idx_contact_created_at on public.contact (created_at desc);

create trigger trg_contact_updated_at
    before update on public.contact
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: faq
-- ---------------------------------------------------------------------
create table if not exists public.faq (
    id              bigint generated always as identity primary key,
    uuid            uuid not null default gen_random_uuid(),
    category        varchar(80) not null default 'Umum',
    question        varchar(300) not null,
    answer          text not null,
    sort_order      integer not null default 0,
    is_active       boolean not null default true,
    view_count      bigint not null default 0,
    created_at      timestamptz not null default timezone('utc', now()),
    updated_at      timestamptz not null default timezone('utc', now()),

    constraint uq_faq_uuid unique (uuid)
);
comment on table public.faq is 'Daftar pertanyaan yang sering diajukan, dikelompokkan per kategori.';

create index if not exists idx_faq_category on public.faq (category);
create index if not exists idx_faq_active on public.faq (is_active);

create trigger trg_faq_updated_at
    before update on public.faq
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: testimonial
-- ---------------------------------------------------------------------
create table if not exists public.testimonial (
    id              bigint generated always as identity primary key,
    uuid            uuid not null default gen_random_uuid(),
    customer_name   varchar(150) not null,
    customer_role   varchar(150),                      -- contoh: "Pemilik UMKM, Raman Utara"
    customer_photo_url text,
    package_id      bigint references public.packages (id) on delete set null,
    rating          smallint not null default 5,
    content         text not null,
    is_featured     boolean not null default false,
    is_published    boolean not null default true,
    sort_order      integer not null default 0,
    created_at      timestamptz not null default timezone('utc', now()),
    updated_at      timestamptz not null default timezone('utc', now()),

    constraint chk_testimonial_rating_range check (rating between 1 and 5)
);
comment on table public.testimonial is 'Testimoni pelanggan yang ditampilkan di halaman Home dan Testimoni.';

create index if not exists idx_testimonial_published on public.testimonial (is_published);
create index if not exists idx_testimonial_featured on public.testimonial (is_featured);

create trigger trg_testimonial_updated_at
    before update on public.testimonial
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: subscriber — newsletter blog & promo
-- ---------------------------------------------------------------------
create table if not exists public.subscriber (
    id                  bigint generated always as identity primary key,
    uuid                uuid not null default gen_random_uuid(),
    email               citext not null,
    name                varchar(150),
    is_verified         boolean not null default false,
    verification_token  varchar(100),
    unsubscribe_token   varchar(100) not null default encode(gen_random_bytes(24), 'hex'),
    subscribed_at       timestamptz not null default timezone('utc', now()),
    unsubscribed_at     timestamptz,
    source              varchar(50) default 'website',

    constraint uq_subscriber_email unique (email)
);
comment on table public.subscriber is 'Daftar subscriber newsletter blog dan informasi promo.';

create index if not exists idx_subscriber_verified on public.subscriber (is_verified);
create index if not exists idx_subscriber_unsub_token on public.subscriber (unsubscribe_token);
