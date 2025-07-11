FROM dunglas/frankenphp:php8.3

# ENV SERVER_NAME=":80"
WORKDIR /app

# Install PHP extensions dan dependencies Chromium & Puppeteer
RUN apt update && apt install -y \
    zip libzip-dev libjpeg-dev libpng-dev libfreetype6-dev \
    libonig-dev libxml2-dev unzip git curl gnupg ca-certificates \
    fonts-liberation libappindicator3-1 libasound2 \
    libatk-bridge2.0-0 libatk1.0-0 libcups2 libdbus-1-3 \
    libgdk-pixbuf2.0-0 libnspr4 libnss3 libx11-xcb1 libxcomposite1 \
    libxdamage1 libxrandr2 xdg-utils wget && \
    docker-php-ext-install pdo pdo_mysql mbstring zip exif bcmath gd pcntl && \
    docker-php-ext-enable zip exif

# Increase PHP memory limit
RUN echo 'memory_limit = 2G' > /usr/local/etc/php/conf.d/memory.ini

# Install Node.js (v18) & Puppeteer
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt install -y nodejs && \
    npm install -g puppeteer

# Set Puppeteer Chrome path
ENV PUPPETEER_EXECUTABLE_PATH="/root/.cache/puppeteer/chrome/linux-*/chrome"

# Copy source code & composer
COPY . .
# Verify artisan exists before proceeding
RUN if [ ! -f artisan ]; then echo "ERROR: artisan file not found!" && exit 1; fi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Octane with explicit version and error handling
RUN composer require laravel/octane:^2.0 --with-all-dependencies --no-interaction \
    && php artisan octane:install --server=frankenphp --no-interaction

# Generate application key if not exists
RUN if [ ! -f .env ]; then cp .env.example .env; fi \
    && php artisan key:generate

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Fix permissions
RUN chown -R www-data:www-data /app \
    && chmod -R 775 storage bootstrap/cache

# Laravel cache opsional
# RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

EXPOSE 8001

USER www-data

CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8001"]
