FROM php:8.3-cli

WORKDIR /app

# ========================
# 🧱 System Dependencies
# ========================
RUN apt update && apt install -y \
    curl zip unzip git gnupg ca-certificates bash \
    libzip-dev libjpeg-dev libpng-dev libfreetype6-dev \
    libonig-dev libxml2-dev \
    fonts-liberation libappindicator3-1 libasound2 \
    libatk-bridge2.0-0 libatk1.0-0 libcups2 libdbus-1-3 \
    libgdk-pixbuf2.0-0 libnspr4 libnss3 libx11-xcb1 libxcomposite1 \
    libxdamage1 libxrandr2 xdg-utils wget

# ========================
# 🧩 PHP Extensions
# ========================
RUN docker-php-ext-install \
    pdo pdo_mysql mbstring zip exif bcmath gd pcntl \
    && docker-php-ext-enable zip exif

# Increase memory limit
RUN echo 'memory_limit = 2G' > /usr/local/etc/php/conf.d/memory.ini

# ========================
# 🌐 Node.js v23
# ========================
ENV PUPPETEER_SKIP_DOWNLOAD=false
RUN curl -fsSL https://deb.nodesource.com/setup_23.x -o nodesource_setup.sh \
    && bash nodesource_setup.sh \
    && apt install -y nodejs

# ========================
# 📦 Composer & Puppeteer
# ========================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy dependency declaration files first (to benefit from Docker cache)
COPY composer.json composer.lock ./
COPY package.json package-lock.json ./

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Install Node modules and build assets
RUN npm install && npm run build

# Copy the rest of the Laravel app
COPY . .

# Chrome binary for puppeteer
RUN mkdir -p /app/chrome \
    && chown -R www-data:www-data /root/.cache/puppeteer \
    && cp $(find /root/.cache/puppeteer -type f -name chrome | head -n1) /app/chrome/chrome \
    && chmod +x /app/chrome/chrome

# Puppeteer auto-detects Chrome binary
ENV CHROME_PATH=/app/chrome/chrome

# Laravel setup
RUN if [ ! -f .env ]; then cp .env.example .env; fi \
    && php artisan key:generate

# Octane with FrankenPHP
RUN composer require laravel/octane:^2.0 --with-all-dependencies --no-interaction \
    && php artisan octane:install --server=frankenphp --no-interaction

# ========================
# ✅ Set Permissions (Only where needed)
# ========================
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# ========================
# 🚀 Runtime Setup
# ========================
EXPOSE 8001

USER www-data

COPY entrypoint.sh /entrypoint.sh
RUN chown www-data:www-data /entrypoint.sh && chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8001"]
