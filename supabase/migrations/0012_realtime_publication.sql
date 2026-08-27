-- =====================================================================
-- Migration: 0012_realtime_publication.sql
-- Tujuan   : Mengaktifkan Supabase Realtime (logical replication) pada
--            tabel-tabel yang butuh live update di sisi client — dashboard
--            monitoring NOC, notifikasi admin, status gangguan publik.
-- =====================================================================

-- Catatan: Supabase secara default menyediakan publication "supabase_realtime".
-- Kita tambahkan tabel secara selektif (TIDAK semua tabel) untuk menghindari
-- overhead replication pada tabel yang tidak butuh live update.

-- Buat publication jika belum ada (biasanya sudah dibuat otomatis oleh Supabase).
do $$
begin
    if not exists (select 1 from pg_publication where pubname = 'supabase_realtime') then
        create publication supabase_realtime;
    end if;
end$$;

-- Network monitoring — dashboard NOC butuh update status real-time.
alter publication supabase_realtime add table public.network_monitor;
alter publication supabase_realtime add table public.network_monitor_history;

-- Trouble report — status gangguan publik & dashboard operator live update.
alter publication supabase_realtime add table public.trouble_report;

-- Maintenance — notifikasi publik saat status maintenance berubah.
alter publication supabase_realtime add table public.maintenance;

-- Announcement — top bar pengumuman live tanpa perlu refresh halaman.
alter publication supabase_realtime add table public.announcement;

-- Notification — notifikasi in-app admin/staff real-time.
alter publication supabase_realtime add table public.notification;

-- Comments — agar komentar baru yang di-approve langsung muncul (live blog).
alter publication supabase_realtime add table public.comments;

-- Pastikan replica identity FULL untuk tabel yang butuh payload DELETE lengkap
-- (default identity hanya mengirim primary key pada event DELETE).
alter table public.network_monitor replica identity full;
alter table public.trouble_report replica identity full;
alter table public.maintenance replica identity full;
alter table public.announcement replica identity full;
