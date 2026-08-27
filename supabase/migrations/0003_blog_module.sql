-- =====================================================================
-- Migration: 0003_blog_module.sql
-- Tujuan   : Modul blog lengkap ala Medium — categories, tags, posts,
--            post_tag (pivot), comments. Mendukung SEO, reading time,
--            view counter, like, bookmark, related post.
-- =====================================================================

-- ---------------------------------------------------------------------
-- TABLE: categories
-- ---------------------------------------------------------------------
create table if not exists public.categories (
    id              bigint generated always as identity primary key,
    uuid            uuid not null default gen_random_uuid(),
    parent_id       bigint references public.categories (id) on delete set null,
    name            varchar(100) not null,
    slug            varchar(120) not null,
    description     text,
    icon            varchar(50),
    cover_image_url text,
    sort_order      integer not null default 0,
    is_active       boolean not null default true,
    seo_title       varchar(160),
    seo_description varchar(320),
    created_at      timestamptz not null default timezone('utc', now()),
    updated_at      timestamptz not null default timezone('utc', now()),

    constraint uq_categories_slug unique (slug)
);
comment on table public.categories is 'Kategori artikel blog, mendukung struktur hierarkis (parent_id).';

create index if not exists idx_categories_parent on public.categories (parent_id);
create index if not exists idx_categories_slug on public.categories (slug);
create index if not exists idx_categories_active on public.categories (is_active);

create trigger trg_categories_updated_at
    before update on public.categories
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: tags
-- ---------------------------------------------------------------------
create table if not exists public.tags (
    id              bigint generated always as identity primary key,
    uuid            uuid not null default gen_random_uuid(),
    name            varchar(60) not null,
    slug            varchar(80) not null,
    usage_count     integer not null default 0,     -- denormalized counter
    created_at      timestamptz not null default timezone('utc', now()),
    updated_at      timestamptz not null default timezone('utc', now()),

    constraint uq_tags_slug unique (slug)
);
comment on table public.tags is 'Tag artikel blog, many-to-many dengan posts melalui post_tag.';

create index if not exists idx_tags_slug on public.tags (slug);
create index if not exists idx_tags_usage on public.tags (usage_count desc);

create trigger trg_tags_updated_at
    before update on public.tags
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: posts
-- ---------------------------------------------------------------------
create table if not exists public.posts (
    id                  bigint generated always as identity primary key,
    uuid                uuid not null default gen_random_uuid(),
    category_id         bigint references public.categories (id) on delete set null,
    author_id           bigint references public.users (id) on delete set null,
    title               varchar(220) not null,
    slug                varchar(250) not null,
    excerpt             varchar(500),
    content             text not null,               -- markdown source
    content_html        text,                         -- cached render HTML
    cover_image_url     text,
    status              content_status not null default 'draft',
    is_featured         boolean not null default false,
    is_pinned           boolean not null default false,
    reading_time_minutes integer not null default 1,
    view_count          bigint not null default 0,
    like_count          bigint not null default 0,
    comment_count       bigint not null default 0,
    bookmark_count      bigint not null default 0,
    share_count         bigint not null default 0,
    seo_title           varchar(160),
    seo_description     varchar(320),
    og_image_url        text,
    canonical_url       text,
    published_at        timestamptz,
    scheduled_at        timestamptz,
    deleted_at          timestamptz,
    created_at          timestamptz not null default timezone('utc', now()),
    updated_at          timestamptz not null default timezone('utc', now()),

    constraint uq_posts_slug unique (slug),
    constraint uq_posts_uuid unique (uuid)
);
comment on table public.posts is 'Artikel blog utama, mendukung markdown, SEO meta, scheduling, dan statistik engagement.';

create index if not exists idx_posts_category on public.posts (category_id);
create index if not exists idx_posts_author on public.posts (author_id);
create index if not exists idx_posts_status on public.posts (status);
create index if not exists idx_posts_published_at on public.posts (published_at desc);
create index if not exists idx_posts_featured on public.posts (is_featured) where is_featured = true;
create index if not exists idx_posts_deleted_at on public.posts (deleted_at);
create index if not exists idx_posts_title_trgm on public.posts using gin (title gin_trgm_ops);
create index if not exists idx_posts_content_trgm on public.posts using gin (content gin_trgm_ops);

create trigger trg_posts_updated_at
    before update on public.posts
    for each row execute function set_updated_at();

-- Auto-hitung reading_time setiap kali content berubah.
create or replace function trg_fn_posts_set_reading_time()
returns trigger
language plpgsql
as $$
begin
    new.reading_time_minutes := calculate_reading_time(new.content);
    if new.slug is null or new.slug = '' then
        new.slug := generate_slug(new.title) || '-' || substr(md5(random()::text), 1, 6);
    end if;
    return new;
end;
$$;

create trigger trg_posts_before_insert_update
    before insert or update of content, title on public.posts
    for each row execute function trg_fn_posts_set_reading_time();

-- ---------------------------------------------------------------------
-- TABLE: post_tag (pivot many-to-many)
-- ---------------------------------------------------------------------
create table if not exists public.post_tag (
    id          bigint generated always as identity primary key,
    post_id     bigint not null references public.posts (id) on delete cascade,
    tag_id      bigint not null references public.tags (id) on delete cascade,
    created_at  timestamptz not null default timezone('utc', now()),

    constraint uq_post_tag unique (post_id, tag_id)
);

create index if not exists idx_post_tag_post on public.post_tag (post_id);
create index if not exists idx_post_tag_tag on public.post_tag (tag_id);

-- Trigger: update usage_count pada tags saat relasi post_tag berubah.
create or replace function trg_fn_tags_sync_usage_count()
returns trigger
language plpgsql
as $$
begin
    if (tg_op = 'INSERT') then
        update public.tags set usage_count = usage_count + 1 where id = new.tag_id;
    elsif (tg_op = 'DELETE') then
        update public.tags set usage_count = greatest(0, usage_count - 1) where id = old.tag_id;
    end if;
    return null;
end;
$$;

create trigger trg_post_tag_after_change
    after insert or delete on public.post_tag
    for each row execute function trg_fn_tags_sync_usage_count();

-- ---------------------------------------------------------------------
-- TABLE: comments (mendukung nested reply via parent_id)
-- ---------------------------------------------------------------------
create table if not exists public.comments (
    id              bigint generated always as identity primary key,
    uuid            uuid not null default gen_random_uuid(),
    post_id         bigint not null references public.posts (id) on delete cascade,
    user_id         bigint references public.users (id) on delete set null,
    parent_id       bigint references public.comments (id) on delete cascade,
    guest_name      varchar(100),                    -- jika komentar tanpa login
    guest_email     citext,
    content         text not null,
    status          comment_status not null default 'pending',
    ip_address      inet,
    user_agent      text,
    like_count      integer not null default 0,
    created_at      timestamptz not null default timezone('utc', now()),
    updated_at      timestamptz not null default timezone('utc', now()),

    constraint chk_comments_author check (user_id is not null or guest_name is not null)
);
comment on table public.comments is 'Komentar artikel blog, mendukung nested reply dan moderasi status.';

create index if not exists idx_comments_post on public.comments (post_id);
create index if not exists idx_comments_parent on public.comments (parent_id);
create index if not exists idx_comments_status on public.comments (status);
create index if not exists idx_comments_user on public.comments (user_id);

create trigger trg_comments_updated_at
    before update on public.comments
    for each row execute function set_updated_at();

-- Trigger: sync comment_count pada posts.
create or replace function trg_fn_posts_sync_comment_count()
returns trigger
language plpgsql
as $$
begin
    if (tg_op = 'INSERT' and new.status = 'approved') then
        update public.posts set comment_count = comment_count + 1 where id = new.post_id;
    elsif (tg_op = 'UPDATE' and old.status != 'approved' and new.status = 'approved') then
        update public.posts set comment_count = comment_count + 1 where id = new.post_id;
    elsif (tg_op = 'UPDATE' and old.status = 'approved' and new.status != 'approved') then
        update public.posts set comment_count = greatest(0, comment_count - 1) where id = new.post_id;
    elsif (tg_op = 'DELETE' and old.status = 'approved') then
        update public.posts set comment_count = greatest(0, comment_count - 1) where id = old.post_id;
    end if;
    return null;
end;
$$;

create trigger trg_comments_after_change
    after insert or update or delete on public.comments
    for each row execute function trg_fn_posts_sync_comment_count();

-- ---------------------------------------------------------------------
-- TABLE: post_likes (tracking like per user/guest, mencegah duplikat)
-- ---------------------------------------------------------------------
create table if not exists public.post_likes (
    id          bigint generated always as identity primary key,
    post_id     bigint not null references public.posts (id) on delete cascade,
    user_id     bigint references public.users (id) on delete cascade,
    fingerprint varchar(64),                          -- hash IP+UA untuk guest
    created_at  timestamptz not null default timezone('utc', now()),

    constraint uq_post_likes_user unique (post_id, user_id),
    constraint chk_post_likes_actor check (user_id is not null or fingerprint is not null)
);

create unique index if not exists uq_post_likes_fingerprint
    on public.post_likes (post_id, fingerprint) where fingerprint is not null;

create index if not exists idx_post_likes_post on public.post_likes (post_id);

create or replace function trg_fn_posts_sync_like_count()
returns trigger
language plpgsql
as $$
begin
    if (tg_op = 'INSERT') then
        update public.posts set like_count = like_count + 1 where id = new.post_id;
    elsif (tg_op = 'DELETE') then
        update public.posts set like_count = greatest(0, like_count - 1) where id = old.post_id;
    end if;
    return null;
end;
$$;

create trigger trg_post_likes_after_change
    after insert or delete on public.post_likes
    for each row execute function trg_fn_posts_sync_like_count();

-- ---------------------------------------------------------------------
-- TABLE: post_bookmarks
-- ---------------------------------------------------------------------
create table if not exists public.post_bookmarks (
    id          bigint generated always as identity primary key,
    post_id     bigint not null references public.posts (id) on delete cascade,
    user_id     bigint not null references public.users (id) on delete cascade,
    created_at  timestamptz not null default timezone('utc', now()),

    constraint uq_post_bookmarks unique (post_id, user_id)
);

create index if not exists idx_post_bookmarks_user on public.post_bookmarks (user_id);

create or replace function trg_fn_posts_sync_bookmark_count()
returns trigger
language plpgsql
as $$
begin
    if (tg_op = 'INSERT') then
        update public.posts set bookmark_count = bookmark_count + 1 where id = new.post_id;
    elsif (tg_op = 'DELETE') then
        update public.posts set bookmark_count = greatest(0, bookmark_count - 1) where id = old.post_id;
    end if;
    return null;
end;
$$;

create trigger trg_post_bookmarks_after_change
    after insert or delete on public.post_bookmarks
    for each row execute function trg_fn_posts_sync_bookmark_count();

-- ---------------------------------------------------------------------
-- VIEW: published_posts — hanya artikel yang sudah terbit & tidak dihapus
-- ---------------------------------------------------------------------
create or replace view public.published_posts as
select p.*, c.name as category_name, c.slug as category_slug,
       u.name as author_name, u.avatar_url as author_avatar
from public.posts p
left join public.categories c on c.id = p.category_id
left join public.users u on u.id = p.author_id
where p.status = 'published'
  and p.deleted_at is null
  and (p.published_at is null or p.published_at <= timezone('utc', now()));

comment on view public.published_posts is 'View artikel yang sudah published dan tidak terhapus, lengkap dengan info kategori & penulis.';

-- ---------------------------------------------------------------------
-- VIEW: trending_posts — 30 hari terakhir, diurutkan skor engagement
-- ---------------------------------------------------------------------
create or replace view public.trending_posts as
select pp.*,
       (pp.view_count * 1 + pp.like_count * 3 + pp.comment_count * 5 + pp.share_count * 4) as engagement_score
from public.published_posts pp
where pp.published_at >= (timezone('utc', now()) - interval '30 days')
order by engagement_score desc;

comment on view public.trending_posts is 'Artikel trending 30 hari terakhir berdasarkan skor engagement gabungan.';

-- ---------------------------------------------------------------------
-- FUNCTION: get_related_posts(post_id, limit) — rekomendasi artikel terkait
-- berdasarkan kategori sama atau kemiripan tag.
-- ---------------------------------------------------------------------
create or replace function public.get_related_posts(p_post_id bigint, p_limit integer default 4)
returns setof public.posts
language sql
stable
as $$
    select distinct p.*
    from public.posts p
    where p.id != p_post_id
      and p.status = 'published'
      and p.deleted_at is null
      and (
            p.category_id = (select category_id from public.posts where id = p_post_id)
            or p.id in (
                select pt2.post_id
                from public.post_tag pt1
                join public.post_tag pt2 on pt2.tag_id = pt1.tag_id and pt2.post_id != pt1.post_id
                where pt1.post_id = p_post_id
            )
      )
    order by p.published_at desc
    limit p_limit;
$$;

comment on function public.get_related_posts(bigint, integer) is 'Mengembalikan artikel terkait berdasarkan kategori sama atau kemiripan tag.';

-- ---------------------------------------------------------------------
-- FUNCTION: search_posts(keyword) — full text search sederhana via trigram
-- ---------------------------------------------------------------------
create or replace function public.search_posts(p_keyword text, p_limit integer default 20, p_offset integer default 0)
returns setof public.posts
language sql
stable
as $$
    select p.*
    from public.posts p
    where p.status = 'published'
      and p.deleted_at is null
      and (
            p.title ilike '%' || p_keyword || '%'
            or p.excerpt ilike '%' || p_keyword || '%'
            or p.content ilike '%' || p_keyword || '%'
      )
    order by p.published_at desc
    limit p_limit offset p_offset;
$$;

comment on function public.search_posts(text, integer, integer) is 'Pencarian artikel blog berdasarkan keyword pada title/excerpt/content.';
