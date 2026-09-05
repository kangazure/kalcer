#!/bin/sh
set -e

cd /app

# =================================================================
# Set safe defaults for cache/session/queue so the app can boot
# even if Dokploy still sends Redis-based env vars from an old config.
# =================================================================
: "${CACHE_STORE:=file}"
: "${SESSION_DRIVER:=file}"
: "${QUEUE_CONNECTION:=sync}"
: "${BROADCAST_CONNECTION:=log}"

# Generate APP_KEY jika belum ada (aman dijalankan berulang, tidak akan
# menimpa key yang sudah ada di .env produksi)
if [ -z "$APP_KEY" ]; then
    echo "[entrypoint] APP_KEY kosong, generate baru..."
    php artisan key:generate --force
fi

# Cache config/route/view untuk performa production.
# Aman untuk gagal diam-diam jika DB belum siap saat container pertama start.
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Jalankan migration otomatis saat container start
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "[entrypoint] Menjalankan migration..."
    php artisan migrate --force || echo "[entrypoint] Migration gagal/dilewati, cek koneksi database."
fi

echo "[entrypoint] Starting supervisord (nginx + php-fpm)..."
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
