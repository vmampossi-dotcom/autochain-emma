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

RUN apk add --no-cache bash git openssh libzip-dev oniguruma-dev libpng-dev icu-dev zlib-dev autoconf build-base make gcc g++ freetype-dev libjpeg-turbo-dev libpng-dev libwebp-dev musl-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl gd intl

# Install composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Ensure the SQLite database directory and file are available in the runtime image
RUN mkdir -p /var/www/html/database && touch /var/www/html/database/database.sqlite

# Copy built frontend assets from node_builder (assumes build outputs to public/)
COPY --from=node_builder /app/public /var/www/html/public

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Ensure Vite production assets (CSS/JS) are present in the runtime image
RUN if [ ! -d /var/www/html/public/build ]; then echo "Vite build output missing"; exit 1; fi

# Permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

ENV APP_ENV=production
ENV PORT=8080

EXPOSE 8080

# Donnez les autorisations nécessaires sur les dossiers de cache et de stockage
RUN chmod -R 777 storage bootstrap/cache

# Commande de démarrage simplifiée
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8080
