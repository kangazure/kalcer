# =====================================================================
# Dockerfile — PT Jaringan Teknologi Sejahtera (JTS)
# Multi-stage build: compile assets -> install PHP deps -> runtime
# =====================================================================

# ---- Stage 1: Build frontend assets ----
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY resources resources
COPY vite.config.ts tailwind.config.js postcss.config.js tsconfig.json ./
COPY public public
RUN npm run build

# ---- Stage 2: Install PHP dependencies ----
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --optimize --no-dev

# ---- Stage 3: Runtime image ----
FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
        nginx supervisor postgresql-dev libzip-dev libpng-dev \
        oniguruma-dev curl-dev freetype-dev libjpeg-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring curl gd zip bcmath opcache

WORKDIR /var/www/html

COPY --from=vendor /app /var/www/html
COPY --from=frontend /app/public/build /var/www/html/public/build

COPY deployment/docker/nginx.conf /etc/nginx/nginx.conf
COPY deployment/docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY deployment/docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
