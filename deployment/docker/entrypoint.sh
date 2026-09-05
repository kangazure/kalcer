#!/bin/sh
set -e
cd /app

# =================================================================
# Paksa safe defaults — override semua env vars Dokploy-injected yang
# bisa jadi titik koneksi ke service yang belum ready (Redis, queue, dll)
# =================================================================
export CACHE_STORE=file
export SESSION_DRIVER=file
export QUEUE_CONNECTION=sync
export BROADCAST_CONNECTION=log

# Jika Dokploy injek MAIL_HOST ke service yang belum ada, pakai log saja
export MAIL_MAILER=log

# Hapus env vars Redis agar Laravel tidak mencoba connect ke Redis yang unavailable
unset REDIS_HOST || true
unset REDIS_PASSWORD || true
unset REDIS_PORT || true

echo "[entrypoint] Environment overridden → cache=file, session=file, queue=sync"

# Generate APP_KEY jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "[entrypoint] APP_KEY kosong, generate baru..."
    php artisan key:generate --force
fi

# Cache config/route/view — aman gagal diam-diam jika DB belum siap
echo "[entrypoint] Caching config..."
php artisan config:cache || echo "[entrypoint] Warning: config:cache gagal, lanjut..."
echo "[entrypoint] Caching routes..."
php artisan route:cache || echo "[entrypoint] Warning: route:cache gagal, lanjut..."
echo "[entrypoint] Caching views..."
php artisan view:cache || echo "[entrypoint] Warning: view:cache gagal, lanjut..."

# Jalankan migration — hanya yang belum berjalan (tabela sudah ada di Supabase)
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "[entrypoint] Menjalankan migration..."
    php artisan migrate --force 2>&1 || echo "[entrypoint] Migration selesai/gagal, lanjut ke supervisor..."
fi

echo "[entrypoint] Starting supervisord (nginx + php-fpm)..."
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
