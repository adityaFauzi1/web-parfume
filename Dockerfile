# ─── Maison Parfum @ Render (Docker + FrankenPHP) ─────────────
FROM dunglas/frankenphp:1-php8.3-bookworm AS base

ENV SERVER_NAME=":80" \
    APP_ENV=production \
    LOG_CHANNEL=stderr \
    LOG_LEVEL=error \
    SESSION_DRIVER=cookie \
    SESSION_ENCRYPT=true \
    CACHE_STORE=file \
    QUEUE_CONNECTION=sync \
    FILESYSTEM_DISK=public

WORKDIR /app

# System deps: postgres driver, gd, zip, node untuk build Vite.
RUN apt-get update && apt-get install -y --no-install-recommends \
      git unzip libpq-dev libicu-dev libzip-dev zlib1g-dev \
      libpng-dev libjpeg-dev libfreetype6-dev nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
      pdo pdo_pgsql pgsql intl zip bcmath gd opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# PHP deps dulu (cache layer) — artisan diblokir sementara.
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Source + build frontend.
COPY . .
RUN npm ci \
 && npm run build \
 && rm -rf node_modules \
 && composer dump-autoload --optimize \
 && php artisan package:discover --ansi || true

# Caddy serve folder public via Laravel front controller.
RUN printf ':{$PORT:80} {\n\troot * /app/public\n\tencode zstd gzip\n\tphp_server\n}\n' > /etc/caddy/Caddyfile

RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 80

# Start: storage link → migrate → seed bila kosong → cache → FrankenPHP.
CMD ["sh", "-c", "php artisan storage:link >/dev/null 2>&1 || true; php artisan migrate --force; if [ \"$(php artisan tinker --execute='echo App\\Models\\User::count();' 2>/dev/null | tail -n1 | tr -d '[:space:]')\" = '0' ]; then php artisan db:seed --force; fi; php artisan config:cache; php artisan route:cache; php artisan view:cache; exec frankenphp run --config /etc/caddy/Caddyfile"]

