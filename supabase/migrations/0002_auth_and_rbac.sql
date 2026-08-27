-- =====================================================================
-- Migration: 0002_auth_and_rbac.sql
-- Tujuan   : Tabel users (terhubung ke auth.users Supabase), roles,
--            permissions, dan pivot RBAC (role_user, permission_role).
-- =====================================================================

-- ---------------------------------------------------------------------
-- TABLE: roles
-- ---------------------------------------------------------------------
create table if not exists public.roles (
    id              bigint generated always as identity primary key,
    uuid            uuid not null default gen_random_uuid(),
    name            varchar(50) not null,
    slug            varchar(50) not null,
    description     text,
    is_system       boolean not null default false, -- role bawaan, tidak boleh dihapus
    created_at      timestamptz not null default timezone('utc', now()),
    updated_at      timestamptz not null default timezone('utc', now()),

    constraint uq_roles_slug unique (slug),
    constraint uq_roles_uuid unique (uuid)
);
comment on table public.roles is 'Daftar role pengguna: Super Admin, Admin, Editor, Marketing, Operator.';

create index if not exists idx_roles_slug on public.roles (slug);

create trigger trg_roles_updated_at
    before update on public.roles
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: permissions
-- ---------------------------------------------------------------------
create table if not exists public.permissions (
    id              bigint generated always as identity primary key,
    name            varchar(100) not null,
    slug            varchar(100) not null,
    module          varchar(50) not null,           -- contoh: 'posts', 'packages'
    description     text,
    created_at      timestamptz not null default timezone('utc', now()),
    updated_at      timestamptz not null default timezone('utc', now()),

    constraint uq_permissions_slug unique (slug)
);
comment on table public.permissions is 'Daftar permission granular per modul (create/read/update/delete/publish dsb).';

create index if not exists idx_permissions_module on public.permissions (module);

create trigger trg_permissions_updated_at
    before update on public.permissions
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: permission_role (pivot many-to-many)
-- ---------------------------------------------------------------------
create table if not exists public.permission_role (
    id              bigint generated always as identity primary key,
    permission_id   bigint not null references public.permissions (id) on delete cascade,
    role_id         bigint not null references public.roles (id) on delete cascade,
    created_at      timestamptz not null default timezone('utc', now()),

    constraint uq_permission_role unique (permission_id, role_id)
);

create index if not exists idx_permission_role_role on public.permission_role (role_id);
create index if not exists idx_permission_role_permission on public.permission_role (permission_id);

-- ---------------------------------------------------------------------
-- TABLE: users
-- Catatan: kolom `auth_user_id` merujuk ke auth.users milik Supabase Auth.
-- Tabel ini menyimpan profil & metadata aplikasi, sedangkan kredensial
-- (password hash, email confirmation, dsb) dikelola oleh Supabase Auth.
-- ---------------------------------------------------------------------
create table if not exists public.users (
    id              bigint generated always as identity primary key,
    uuid            uuid not null default gen_random_uuid(),
    auth_user_id    uuid unique,                    -- FK logis ke auth.users.id
    role_id         bigint references public.roles (id) on delete set null,
    name            varchar(150) not null,
    email           citext not null,
    phone           varchar(20),
    avatar_url      text,
    bio             text,
    position        varchar(100),                   -- jabatan internal (untuk tim)
    status          user_status not null default 'pending',
    email_verified_at timestamptz,
    last_login_at   timestamptz,
    last_login_ip   inet,
    two_factor_enabled boolean not null default false,
    two_factor_secret  text,
    remember_token  varchar(100),
    created_by      bigint references public.users (id) on delete set null,
    deleted_at      timestamptz,                    -- soft delete
    created_at      timestamptz not null default timezone('utc', now()),
    updated_at      timestamptz not null default timezone('utc', now()),

    constraint uq_users_uuid unique (uuid),
    constraint uq_users_email unique (email),
    constraint chk_users_phone_format check (phone is null or phone ~ '^[0-9+\-\s()]{6,20}$')
);
comment on table public.users is 'Profil pengguna aplikasi (admin & staff), terhubung 1:1 ke Supabase auth.users via auth_user_id.';

create index if not exists idx_users_role on public.users (role_id);
create index if not exists idx_users_status on public.users (status);
create index if not exists idx_users_email on public.users (email);
create index if not exists idx_users_deleted_at on public.users (deleted_at);
create index if not exists idx_users_auth_user_id on public.users (auth_user_id);

create trigger trg_users_updated_at
    before update on public.users
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: role_user (pivot many-to-many — mendukung multi-role per user)
-- ---------------------------------------------------------------------
create table if not exists public.role_user (
    id              bigint generated always as identity primary key,
    user_id         bigint not null references public.users (id) on delete cascade,
    role_id         bigint not null references public.roles (id) on delete cascade,
    assigned_by     bigint references public.users (id) on delete set null,
    created_at      timestamptz not null default timezone('utc', now()),

    constraint uq_role_user unique (user_id, role_id)
);

create index if not exists idx_role_user_user on public.role_user (user_id);
create index if not exists idx_role_user_role on public.role_user (role_id);

-- ---------------------------------------------------------------------
-- FUNCTION: current_app_user_id()
-- Helper untuk RLS policy — memetakan auth.uid() (Supabase Auth) ke
-- baris public.users yang sesuai.
-- ---------------------------------------------------------------------
create or replace function public.current_app_user_id()
returns bigint
language sql
stable
security definer
as $$
    select id from public.users where auth_user_id = auth.uid() limit 1;
$$;

comment on function public.current_app_user_id() is 'Memetakan auth.uid() Supabase ke primary key public.users untuk dipakai RLS policy.';

-- ---------------------------------------------------------------------
-- FUNCTION: current_app_user_role_slug()
-- ---------------------------------------------------------------------
create or replace function public.current_app_user_role_slug()
returns text
language sql
stable
security definer
as $$
    select r.slug
    from public.users u
    join public.roles r on r.id = u.role_id
    where u.auth_user_id = auth.uid()
    limit 1;
$$;

comment on function public.current_app_user_role_slug() is 'Mengambil slug role user yang sedang login, untuk RLS policy berbasis role.';

-- ---------------------------------------------------------------------
-- FUNCTION: user_has_permission(permission_slug)
-- ---------------------------------------------------------------------
create or replace function public.user_has_permission(p_permission_slug text)
returns boolean
language sql
stable
security definer
as $$
    select exists (
        select 1
        from public.users u
        join public.permission_role pr on pr.role_id = u.role_id
        join public.permissions p on p.id = pr.permission_id
        where u.auth_user_id = auth.uid()
          and p.slug = p_permission_slug
    );
$$;

comment on function public.user_has_permission(text) is 'Mengecek apakah user yang sedang login memiliki permission tertentu, dipakai dalam RLS policy granular.';
