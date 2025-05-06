FROM php:php8.3-cli

ENV SERVER_NAME=":80"
WORKDIR /app

# Install PHP extensions
RUN apt update && apt install -y \
    zip libzip-dev libjpeg-dev libpng-dev libfreetype6-dev \
    libonig-dev libxml2-dev unzip git curl && \
    docker-php-ext-install pdo pdo_mysql mbstring zip exif bcmath gd && \
    docker-php-ext-enable zip exif

# Copy source code
COPY . /app

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Laravel caches (opsional, pastikan .env sudah disiapkan)
# RUN php artisan config:cache && php artisan route:cache && php artisan view:cache
