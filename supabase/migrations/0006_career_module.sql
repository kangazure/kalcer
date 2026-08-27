-- =====================================================================
-- Migration: 0006_career_module.sql
-- Tujuan   : Modul karir — lowongan kerja & lamaran pelamar.
-- =====================================================================

-- ---------------------------------------------------------------------
-- TABLE: career — daftar lowongan pekerjaan
-- ---------------------------------------------------------------------
create table if not exists public.career (
    id                  bigint generated always as identity primary key,
    uuid                uuid not null default gen_random_uuid(),
    title               varchar(180) not null,
    slug                varchar(200) not null,
    department          varchar(100),
    location            varchar(150) not null default 'Lampung Timur',
    job_type            job_type not null default 'full_time',
    description         text not null,
    requirements        jsonb not null default '[]'::jsonb,
    responsibilities    jsonb not null default '[]'::jsonb,
    benefits            jsonb not null default '[]'::jsonb,
    salary_min          numeric(12, 2),
    salary_max          numeric(12, 2),
    salary_is_negotiable boolean not null default true,
    vacancy_count       integer not null default 1,
    is_active           boolean not null default true,
    closes_at           timestamptz,
    created_by          bigint references public.users (id) on delete set null,
    created_at          timestamptz not null default timezone('utc', now()),
    updated_at          timestamptz not null default timezone('utc', now()),

    constraint uq_career_slug unique (slug)
);
comment on table public.career is 'Lowongan pekerjaan yang dibuka oleh perusahaan.';

create index if not exists idx_career_slug on public.career (slug);
create index if not exists idx_career_active on public.career (is_active);
create index if not exists idx_career_job_type on public.career (job_type);

create trigger trg_career_updated_at
    before update on public.career
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: job_application — lamaran yang masuk untuk tiap lowongan
-- ---------------------------------------------------------------------
create table if not exists public.job_application (
    id                  bigint generated always as identity primary key,
    uuid                uuid not null default gen_random_uuid(),
    career_id           bigint not null references public.career (id) on delete cascade,
    full_name           varchar(150) not null,
    email               citext not null,
    phone               varchar(20) not null,
    cover_letter        text,
    resume_media_id     bigint references public.media (id) on delete set null,
    portfolio_url       text,
    linkedin_url        text,
    expected_salary     numeric(12, 2),
    status              application_status not null default 'submitted',
    reviewer_id         bigint references public.users (id) on delete set null,
    reviewer_notes      text,
    ip_address          inet,
    created_at          timestamptz not null default timezone('utc', now()),
    updated_at          timestamptz not null default timezone('utc', now()),

    constraint chk_job_application_phone_format check (phone ~ '^[0-9+\-\s()]{6,20}$')
);
comment on table public.job_application is 'Lamaran kerja yang masuk untuk setiap lowongan di tabel career.';

create index if not exists idx_job_application_career on public.job_application (career_id);
create index if not exists idx_job_application_status on public.job_application (status);
create index if not exists idx_job_application_email on public.job_application (email);

create trigger trg_job_application_updated_at
    before update on public.job_application
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- VIEW: open_career — lowongan yang masih aktif & belum tutup
-- ---------------------------------------------------------------------
create or replace view public.open_career as
select c.*,
       (select count(*) from public.job_application ja where ja.career_id = c.id) as total_applicants
from public.career c
where c.is_active = true
  and (c.closes_at is null or c.closes_at > timezone('utc', now()));

comment on view public.open_career is 'Lowongan kerja yang masih dibuka, dengan jumlah pelamar masuk.';
