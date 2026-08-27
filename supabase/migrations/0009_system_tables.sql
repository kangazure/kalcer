-- =====================================================================
-- Migration: 0009_system_tables.sql
-- Tujuan   : Tabel sistem — notification, settings, analytics, visitor,
--            activity_logs. Mendukung audit trail dan konfigurasi global.
-- =====================================================================

-- ---------------------------------------------------------------------
-- TABLE: notification — notifikasi in-app untuk admin/staff
-- ---------------------------------------------------------------------
create table if not exists public.notification (
    id              bigint generated always as identity primary key,
    uuid            uuid not null default gen_random_uuid(),
    user_id         bigint references public.users (id) on delete cascade,
    channel         notification_channel not null default 'database',
    type            varchar(80) not null,              -- contoh: 'new_contact', 'new_application'
    title           varchar(200) not null,
    body            text,
    action_url      text,
    is_read         boolean not null default false,
    read_at         timestamptz,
    metadata        jsonb not null default '{}'::jsonb,
    created_at      timestamptz not null default timezone('utc', now())
);
comment on table public.notification is 'Notifikasi in-app untuk pengguna admin/staff (lead baru, lamaran baru, gangguan, dsb).';

create index if not exists idx_notification_user on public.notification (user_id);
create index if not exists idx_notification_read on public.notification (user_id, is_read);
create index if not exists idx_notification_created on public.notification (created_at desc);

-- ---------------------------------------------------------------------
-- TABLE: settings — konfigurasi global website (key-value, jsonb value)
-- ---------------------------------------------------------------------
create table if not exists public.settings (
    id              bigint generated always as identity primary key,
    group_name      varchar(60) not null default 'general', -- general | seo | social | smtp | integration
    key             varchar(100) not null,
    value           jsonb,
    label           varchar(150),
    description     text,
    is_public       boolean not null default false,    -- boleh diakses tanpa auth (misal alamat, telp)
    updated_by      bigint references public.users (id) on delete set null,
    created_at      timestamptz not null default timezone('utc', now()),
    updated_at      timestamptz not null default timezone('utc', now()),

    constraint uq_settings_group_key unique (group_name, key)
);
comment on table public.settings is 'Konfigurasi global website dalam format key-value per grup (general, seo, social, smtp, integration).';

create index if not exists idx_settings_group on public.settings (group_name);
create index if not exists idx_settings_public on public.settings (is_public);

create trigger trg_settings_updated_at
    before update on public.settings
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: visitor — log kunjungan untuk analytics ringan internal
-- ---------------------------------------------------------------------
create table if not exists public.visitor (
    id              bigint generated always as identity primary key,
    session_id      varchar(64) not null,
    ip_address      inet,
    user_agent      text,
    referrer        text,
    landing_page    text,
    country         varchar(80),
    city            varchar(80),
    device_type     varchar(20),                       -- desktop | mobile | tablet
    browser         varchar(60),
    os              varchar(60),
    visited_at      timestamptz not null default timezone('utc', now())
);
comment on table public.visitor is 'Log kunjungan pengunjung website untuk analytics internal ringan (selain Google Analytics).';

create index if not exists idx_visitor_session on public.visitor (session_id);
create index if not exists idx_visitor_visited_at on public.visitor (visited_at desc);
create index if not exists idx_visitor_device on public.visitor (device_type);

-- ---------------------------------------------------------------------
-- TABLE: analytics — agregat harian (page view, unique visitor, dst)
-- ---------------------------------------------------------------------
create table if not exists public.analytics (
    id                  bigint generated always as identity primary key,
    metric_date         date not null,
    page_path           varchar(255),
    page_views          bigint not null default 0,
    unique_visitors     bigint not null default 0,
    avg_duration_seconds integer not null default 0,
    bounce_rate         numeric(5, 2),
    created_at          timestamptz not null default timezone('utc', now()),

    constraint uq_analytics_date_path unique (metric_date, page_path)
);
comment on table public.analytics is 'Agregat statistik harian per halaman, dihasilkan oleh scheduler dari tabel visitor.';

create index if not exists idx_analytics_date on public.analytics (metric_date desc);
create index if not exists idx_analytics_path on public.analytics (page_path);

-- ---------------------------------------------------------------------
-- TABLE: activity_logs — audit trail seluruh aksi penting di admin
-- ---------------------------------------------------------------------
create table if not exists public.activity_logs (
    id              bigint generated always as identity primary key,
    uuid            uuid not null default gen_random_uuid(),
    user_id         bigint references public.users (id) on delete set null,
    log_name        varchar(60) not null default 'default',
    description     varchar(300) not null,
    subject_type    varchar(100),                       -- model yang terkena aksi
    subject_id      bigint,
    event           varchar(30) not null,                -- created | updated | deleted | login | logout
    properties      jsonb not null default '{}'::jsonb,  -- old/new values
    ip_address      inet,
    user_agent      text,
    created_at      timestamptz not null default timezone('utc', now())
);
comment on table public.activity_logs is 'Audit trail seluruh aksi penting yang dilakukan user di dashboard admin.';

create index if not exists idx_activity_logs_user on public.activity_logs (user_id);
create index if not exists idx_activity_logs_subject on public.activity_logs (subject_type, subject_id);
create index if not exists idx_activity_logs_created on public.activity_logs (created_at desc);
create index if not exists idx_activity_logs_event on public.activity_logs (event);

-- ---------------------------------------------------------------------
-- FUNCTION: log_activity(...) — helper dipanggil dari trigger modul lain
-- ---------------------------------------------------------------------
create or replace function public.log_activity(
    p_user_id bigint,
    p_description varchar,
    p_subject_type varchar,
    p_subject_id bigint,
    p_event varchar,
    p_properties jsonb default '{}'::jsonb
)
returns void
language plpgsql
as $$
begin
    insert into public.activity_logs (user_id, description, subject_type, subject_id, event, properties)
    values (p_user_id, p_description, p_subject_type, p_subject_id, p_event, p_properties);
end;
$$;

comment on function public.log_activity(bigint, varchar, varchar, bigint, varchar, jsonb) is 'Helper terpusat untuk mencatat audit trail dari trigger modul manapun.';
