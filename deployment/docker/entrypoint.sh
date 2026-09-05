#!/bin/sh
set -e
cd /app

# =================================================================
# Override semua env vars Redis/cache/session yang Dokploy inject
# supaya app tidak crash karena service yang belum ready
# =================================================================
export CACHE_STORE=file
export SESSION_DRIVER=file
export QUEUE_CONNECTION=sync
export BROADCAST_CONNECTION=log
export MAIL_MAILER=log

# Hapus variabel Redis yang dipaksa Dokploy
unset REDIS_HOST || true
unset REDIS_PASSWORD || true
unset REDIS_PORT || true
unset REDIS_URL || true

echo "[entrypoint] Environment overridden: cache=file, session=file, queue=sync"

# Generate APP_KEY jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "[entrypoint] APP_KEY kosong, generate baru..."
    php artisan key:generate --force
fi

# Cache config/route/view
echo "[entrypoint] Caching config..."
php artisan config:cache 2>&1 || echo "[entrypoint] Warning: config:cache gagal"
echo "[entrypoint] Caching routes..."
php artisan route:cache 2>&1 || echo "[entrypoint] Warning: route:cache gagal"
echo "[entrypoint] Caching views..."
php artisan view:cache 2>&1 || echo "[entrypoint] Warning: view:cache gagal"

# Migration —skip jika RUN_MIGRATIONS=false (Dokploy default)
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "[entrypoint] Menjalankan migration..."
    php artisan migrate --force 2>&1 || echo "[entrypoint] Warning: migration dilewati/gagal"
fi

echo "[entrypoint] Setup selesai."

# =================================================================
# Jika ada supervisord lain yang sudah jalan (Dokploy-injected),
# jangan start supervisord kita sendiri — cukup tunggu dan biarkan
# supervisord Dokploy yang manage nginx + php-fpm.
# Entry point tetap jalan sebagai child process.
# =================================================================
if [ -f /var/run/supervisord.pid ]; then
    echo "[entrypoint] Supervisord lain sudah jalan (Dokploy), skip start supervisord lokal."
    echo "[entrypoint] Menunggu proses lain mengelola container..."
    # Tunggu sampai ada sinyal stop, atau timeout 3600 detik
    sleep 3600 &
    wait $!
else
    echo "[entrypoint] Starting supervisord (nginx + php-fpm)..."
    exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
fi
