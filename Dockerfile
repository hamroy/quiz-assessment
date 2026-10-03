# syntax=docker/dockerfile:1

# ============================================
# 1. PHP dependencies
# ============================================
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts


# ============================================
# 2. Frontend build
# ============================================
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.* ./

RUN npm run build


# ============================================
# 3. Production
# ============================================
FROM php:8.4-cli-alpine AS production

WORKDIR /var/www/html

RUN apk add --no-cache \
    libpq \
    icu \
    libzip \
    oniguruma

RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    postgresql-dev \
    icu-dev \
    libzip-dev \
    oniguruma-dev

RUN docker-php-ext-install \
    pdo_pgsql \
    intl \
    mbstring \
    zip

RUN apk del .build-deps

# Composer dependencies
COPY --from=vendor /app/vendor ./vendor

# Application source
COPY . .

# Vite assets
COPY --from=frontend /app/public/build ./public/build

# Laravel writable directories
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

EXPOSE 8080

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]