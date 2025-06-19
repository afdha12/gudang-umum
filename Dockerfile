FROM dunglas/frankenphp:php8.3

ENV SERVER_NAME=":80"
WORKDIR /app

# Install PHP extensions dan dependencies Chromium & Puppeteer
RUN apt update && apt install -y \
    zip libzip-dev libjpeg-dev libpng-dev libfreetype6-dev \
    libonig-dev libxml2-dev unzip git curl gnupg ca-certificates \
    fonts-liberation libappindicator3-1 libasound2 \
    libatk-bridge2.0-0 libatk1.0-0 libcups2 libdbus-1-3 \
    libgdk-pixbuf2.0-0 libnspr4 libnss3 libx11-xcb1 libxcomposite1 \
    libxdamage1 libxrandr2 xdg-utils wget && \
    docker-php-ext-install pdo pdo_mysql mbstring zip exif bcmath gd && \
    docker-php-ext-enable zip exif

# Install Node.js (v18) & Puppeteer
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt install -y nodejs && \
    npm install -g puppeteer

# Set Puppeteer Chrome path
ENV PUPPETEER_EXECUTABLE_PATH="/root/.cache/puppeteer/chrome/linux-*/chrome"

# Copy source code & composer
COPY . /app
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Laravel cache opsional
# RUN php artisan config:cache && php artisan route:cache && php artisan view:cache
