# Multi-stage Dockerfile for Laravel (Node builder + PHP-FPM runtime)
## Builds frontend assets with Node, installs PHP deps and Composer,
## copies built assets and starts the app with artisan serve.

FROM node:18-alpine AS node_builder
WORKDIR /app

# Install dependencies and build frontend assets
COPY package*.json package-lock.json ./
RUN npm ci --silent
COPY . .
RUN npm run build --silent

FROM php:8.2-fpm-alpine

RUN apk add --no-cache bash git openssh libzip-dev oniguruma-dev libpng-dev icu-dev zlib-dev autoconf build-base make gcc g++ \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl gd intl || true

# Install composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Copy built frontend assets from node_builder (assumes build outputs to public/)
COPY --from=node_builder /app/public /var/www/html/public

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist || true

# Permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

ENV APP_ENV=production
ENV PORT=8080

# Start using artisan serve so Render can bind to the provided PORT
CMD ["sh", "-lc", "php artisan serve --host 0.0.0.0 --port ${PORT:-8080}"]
