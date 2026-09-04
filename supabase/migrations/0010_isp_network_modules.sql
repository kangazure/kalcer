-- =====================================================================
-- Migration: 0010_isp_network_modules.sql
-- Tujuan   : Modul khas ISP — network monitoring (POP/backbone), coverage
--            area, maintenance schedule, dan trouble report (gangguan).
-- =====================================================================

-- ---------------------------------------------------------------------
-- TABLE: coverage_area — wilayah jangkauan layanan (polygon/titik)
-- ---------------------------------------------------------------------
create table if not exists public.coverage_area (
    id                  bigint generated always as identity primary key,
    uuid                uuid not null default gen_random_uuid(),
    region_name         varchar(150) not null,          -- contoh: "Raman Utara"
    district            varchar(100),                    -- kecamatan
    regency             varchar(100) not null default 'Lampung Timur',
    province            varchar(100) not null default 'Lampung',
    center_latitude     numeric(10, 6) not null,
    center_longitude    numeric(10, 6) not null,
    radius_meters       integer not null default 3000,
    polygon_geojson     jsonb,                            -- area presisi (opsional)
    coverage_status     varchar(20) not null default 'available', -- available | partial | planned
    pop_id              bigint,                           -- FK ditambahkan setelah tabel network_monitor dibuat
    is_active           boolean not null default true,
    created_at          timestamptz not null default timezone('utc', now()),
    updated_at          timestamptz not null default timezone('utc', now()),

    constraint chk_coverage_status check (coverage_status in ('available', 'partial', 'planned'))
);
comment on table public.coverage_area is 'Wilayah jangkauan layanan internet JTS, dipakai untuk fitur "Cek Jangkauan" di peta.';

create index if not exists idx_coverage_area_active on public.coverage_area (is_active);
create index if not exists idx_coverage_area_status on public.coverage_area (coverage_status);
create index if not exists idx_coverage_area_location on public.coverage_area (center_latitude, center_longitude);

create trigger trg_coverage_area_updated_at
    before update on public.coverage_area
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: network_monitor — status POP (Point of Presence) & perangkat backbone
-- ---------------------------------------------------------------------
create table if not exists public.network_monitor (
    id                  bigint generated always as identity primary key,
    uuid                uuid not null default gen_random_uuid(),
    node_name           varchar(150) not null,           -- contoh: "POP Raman Utara"
    node_type           varchar(30) not null default 'pop', -- pop | backbone | core | access_point
    ip_address          inet,
    latitude            numeric(10, 6),
    longitude           numeric(10, 6),
    status              monitor_status not null default 'unknown',
    bandwidth_capacity_mbps integer,
    bandwidth_usage_mbps    numeric(10, 2),
    latency_ms          numeric(8, 2),
    packet_loss_percent numeric(5, 2),
    uptime_percent      numeric(5, 2),
    last_checked_at     timestamptz,
    last_down_at        timestamptz,
    parent_node_id      bigint references public.network_monitor (id) on delete set null, -- topologi backbone->pop
    created_at          timestamptz not null default timezone('utc', now()),
    updated_at          timestamptz not null default timezone('utc', now())
);
comment on table public.network_monitor is 'Status real-time node jaringan (POP, backbone, core router) untuk dashboard monitoring NOC.';

create index if not exists idx_network_monitor_status on public.network_monitor (status);
create index if not exists idx_network_monitor_type on public.network_monitor (node_type);
create index if not exists idx_network_monitor_parent on public.network_monitor (parent_node_id);

create trigger trg_network_monitor_updated_at
    before update on public.network_monitor
    for each row execute function set_updated_at();

-- Tambahkan FK pop_id pada coverage_area sekarang network_monitor sudah ada.
alter table public.coverage_area
    add constraint fk_coverage_area_pop
    foreign key (pop_id) references public.network_monitor (id) on delete set null;

create index if not exists idx_coverage_area_pop on public.coverage_area (pop_id);

-- ---------------------------------------------------------------------
-- TABLE: network_monitor_history — histori bandwidth/latency time-series
-- (dipisah dari tabel utama supaya tabel utama tetap ringan untuk realtime)
-- ---------------------------------------------------------------------
create table if not exists public.network_monitor_history (
    id                      bigint generated always as identity primary key,
    node_id                 bigint not null references public.network_monitor (id) on delete cascade,
    bandwidth_usage_mbps    numeric(10, 2),
    latency_ms              numeric(8, 2),
    packet_loss_percent     numeric(5, 2),
    status                  monitor_status not null,
    recorded_at             timestamptz not null default timezone('utc', now())
);
comment on table public.network_monitor_history is 'Time-series histori bandwidth/latency per node, sumber data untuk grafik trafik.';

create index if not exists idx_nm_history_node_time on public.network_monitor_history (node_id, recorded_at desc);

-- Index terbaru 7 hari: gunakan partial index dengan kolom frozen_start
-- agar predicate tetap immutable. Jika query butuh range dinamis, filter
-- di aplikasi.
create index if not exists idx_nm_history_recent on public.network_monitor_history (recorded_at desc);

-- ---------------------------------------------------------------------
-- TABLE: maintenance — jadwal maintenance terencana
-- ---------------------------------------------------------------------
create table if not exists public.maintenance (
    id                  bigint generated always as identity primary key,
    uuid                uuid not null default gen_random_uuid(),
    title               varchar(200) not null,
    description         text not null,
    affected_areas      jsonb not null default '[]'::jsonb,  -- array region_name dari coverage_area
    affected_node_ids   jsonb not null default '[]'::jsonb,  -- array id network_monitor
    status              maintenance_status not null default 'scheduled',
    scheduled_start      timestamptz not null,
    scheduled_end        timestamptz not null,
    actual_start         timestamptz,
    actual_end           timestamptz,
    created_by           bigint references public.users (id) on delete set null,
    created_at           timestamptz not null default timezone('utc', now()),
    updated_at           timestamptz not null default timezone('utc', now()),

    constraint chk_maintenance_schedule check (scheduled_start < scheduled_end)
);
comment on table public.maintenance is 'Jadwal maintenance terencana yang dapat mempengaruhi layanan di area tertentu.';

create index if not exists idx_maintenance_status on public.maintenance (status);
create index if not exists idx_maintenance_schedule on public.maintenance (scheduled_start, scheduled_end);

create trigger trg_maintenance_updated_at
    before update on public.maintenance
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TABLE: trouble_report — laporan gangguan dari pelanggan / sistem monitoring
-- ---------------------------------------------------------------------
create table if not exists public.trouble_report (
    id                  bigint generated always as identity primary key,
    uuid                uuid not null default gen_random_uuid(),
    reporter_name       varchar(150),
    reporter_phone      varchar(20),
    reporter_email      citext,
    customer_id_number  varchar(50),                     -- nomor pelanggan, jika ada
    node_id             bigint references public.network_monitor (id) on delete set null,
    region_name         varchar(150),
    title               varchar(200) not null,
    description         text not null,
    severity            trouble_severity not null default 'medium',
    status              trouble_status not null default 'open',
    assigned_to         bigint references public.users (id) on delete set null,
    resolution_notes    text,
    reported_at         timestamptz not null default timezone('utc', now()),
    resolved_at         timestamptz,
    ip_address          inet,
    created_at          timestamptz not null default timezone('utc', now()),
    updated_at          timestamptz not null default timezone('utc', now()),

    constraint chk_trouble_report_phone_format check (reporter_phone is null or reporter_phone ~ '^[0-9+\-\s()]{6,20}$')
);
comment on table public.trouble_report is 'Laporan gangguan jaringan dari pelanggan, ditampilkan agregat di halaman publik "Status Gangguan".';

create index if not exists idx_trouble_report_status on public.trouble_report (status);
create index if not exists idx_trouble_report_severity on public.trouble_report (severity);
create index if not exists idx_trouble_report_node on public.trouble_report (node_id);
create index if not exists idx_trouble_report_region on public.trouble_report (region_name);
create index if not exists idx_trouble_report_reported_at on public.trouble_report (reported_at desc);

create trigger trg_trouble_report_updated_at
    before update on public.trouble_report
    for each row execute function set_updated_at();

-- ---------------------------------------------------------------------
-- TRIGGER: auto-update status node jadi 'offline' & catat last_down_at
-- saat trouble_report critical baru masuk untuk node tersebut.
-- ---------------------------------------------------------------------
create or replace function trg_fn_trouble_report_affect_node()
returns trigger
language plpgsql
as $$
begin
    if new.node_id is not null and new.severity = 'critical' and new.status = 'open' then
        update public.network_monitor
        set status = 'degraded', last_down_at = timezone('utc', now())
        where id = new.node_id and status != 'offline';
    end if;
    return new;
end;
$$;

create trigger trg_trouble_report_after_insert
    after insert on public.trouble_report
    for each row execute function trg_fn_trouble_report_affect_node();

-- ---------------------------------------------------------------------
-- VIEW: network_status_summary — ringkasan status jaringan untuk halaman
-- publik "Status Gangguan" (tanpa expose data sensitif internal)
-- ---------------------------------------------------------------------
create or replace view public.network_status_summary as
select
    nm.id,
    nm.node_name,
    nm.node_type,
    nm.status,
    nm.uptime_percent,
    nm.last_checked_at,
    count(distinct ca.id) as covered_regions,
    count(distinct tr.id) filter (where tr.status in ('open', 'investigating')) as active_trouble_reports
from public.network_monitor nm
left join public.coverage_area ca on ca.pop_id = nm.id
left join public.trouble_report tr on tr.node_id = nm.id
group by nm.id, nm.node_name, nm.node_type, nm.status, nm.uptime_percent, nm.last_checked_at;

comment on view public.network_status_summary is 'Ringkasan status jaringan per node untuk ditampilkan di halaman publik Status Gangguan, tanpa data sensitif.';

-- ---------------------------------------------------------------------
-- VIEW: active_maintenance_public — maintenance yang relevan ditampilkan publik
-- ---------------------------------------------------------------------
create or replace view public.active_maintenance_public as
select id, title, description, affected_areas, status, scheduled_start, scheduled_end, actual_start, actual_end
from public.maintenance
where status in ('scheduled', 'ongoing')
order by scheduled_start asc;

comment on view public.active_maintenance_public is 'Maintenance yang scheduled/ongoing, ditampilkan ke pengunjung publik sebagai notifikasi transparansi.';

-- ---------------------------------------------------------------------
-- FUNCTION: get_bandwidth_chart_data(node_id, hours) — agregat untuk Chart.js
-- ---------------------------------------------------------------------
create or replace function public.get_bandwidth_chart_data(p_node_id bigint, p_hours integer default 24)
returns table (
    bucket_time timestamptz,
    avg_bandwidth_mbps numeric,
    avg_latency_ms numeric,
    avg_packet_loss numeric
)
language sql
stable
as $$
    select
        date_trunc('hour', recorded_at) as bucket_time,
        round(avg(bandwidth_usage_mbps), 2) as avg_bandwidth_mbps,
        round(avg(latency_ms), 2) as avg_latency_ms,
        round(avg(packet_loss_percent), 2) as avg_packet_loss
    from public.network_monitor_history
    where node_id = p_node_id
      and recorded_at >= (timezone('utc', now()) - (p_hours || ' hours')::interval)
    group by bucket_time
    order by bucket_time asc;
$$;

comment on function public.get_bandwidth_chart_data(bigint, integer) is 'Agregat data bandwidth/latency per jam untuk rendering grafik Chart.js di dashboard monitoring.';

-- ---------------------------------------------------------------------
-- FUNCTION: check_coverage_by_point(lat, lng) — cek jangkauan berdasar titik
-- koordinat (dipakai endpoint "Cek Jangkauan")
-- ---------------------------------------------------------------------
create or replace function public.check_coverage_by_point(p_lat numeric, p_lng numeric)
returns table (
    area_id bigint,
    region_name varchar,
    coverage_status varchar,
    distance_meters numeric
)
language sql
stable
as $$
    select
        ca.id,
        ca.region_name,
        ca.coverage_status,
        round(
            (6371000 * acos(
                cos(radians(p_lat)) * cos(radians(ca.center_latitude)) *
                cos(radians(ca.center_longitude) - radians(p_lng)) +
                sin(radians(p_lat)) * sin(radians(ca.center_latitude))
            ))::numeric, 2
        ) as distance_meters
    from public.coverage_area ca
    where ca.is_active = true
    order by distance_meters asc
    limit 5;
$$;

comment on function public.check_coverage_by_point(numeric, numeric) is 'Menghitung jarak titik koordinat pengguna ke wilayah jangkauan terdekat (haversine formula) untuk fitur Cek Jangkauan.';
