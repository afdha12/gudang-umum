# Dockerfile for Laravel Octane with FrankenPHP and wkhtmltopdf
FROM php:8.3-cli

WORKDIR /app

# ========================
# 🧱 System Dependencies
# ========================
RUN apt update && apt install -y \
    curl zip unzip git wget gnupg ca-certificates bash \
    libzip-dev libjpeg-dev libpng-dev libfreetype6-dev \
    libonig-dev libxml2-dev \
    xfonts-75dpi xfonts-base fontconfig libjpeg62-turbo

# ========================
# 🧩 PHP Extensions
# ========================
RUN docker-php-ext-install \
    pdo pdo_mysql mbstring zip exif bcmath gd pcntl \
    && docker-php-ext-enable zip exif

# Increase PHP memory limit
RUN echo 'memory_limit = 2G' > /usr/local/etc/php/conf.d/memory.ini

# ========================
# 📦 Composer
# ========================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy dependency declaration files first
COPY composer.json composer.lock ./

# ✅ Composer install without scripts (to avoid artisan error)
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# ========================
# 🌐 wkhtmltopdf Installation
# ========================
RUN wget https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6.1-3/wkhtmltox_0.12.6.1-3.bookworm_amd64.deb \
    && apt install -y ./wkhtmltox_0.12.6.1-3.bookworm_amd64.deb \
    && ln -s /usr/local/bin/wkhtmltopdf /usr/bin/wkhtmltopdf \
    && rm wkhtmltox_0.12.6.1-3.bookworm_amd64.deb

# ========================
# 🧱 Node.js (optional, if using Vite or frontend assets)
# ========================
RUN curl -fsSL https://deb.nodesource.com/setup_22.x -o nodesource_setup.sh \
    && bash nodesource_setup.sh \
    && apt install -y nodejs \
    && rm nodesource_setup.sh

# Copy package files if they exist
COPY ["package.json", "package-lock.json", "./"]
RUN npm ci

# ========================
# 📦 Laravel App Setup
# ========================
COPY . .
# RUN npm install && npm run build
RUN npm run build
# Environment setup
RUN if [ ! -f .env ]; then cp .env.prod .env; fi

# Generate app key first
RUN php artisan key:generate --force

# ========================
# 🚀 Laravel Octane + FrankenPHP
# ========================
RUN php artisan octane:install --server=frankenphp --no-interaction

# Run artisan commands that require database (with error handling)
RUN php artisan storage:link || true

# Set file permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chmod 644 .env.prod \
    && touch .env \
    && chown www-data:www-data .env \
    && chmod 644 .env \
    && chmod 644 .env.prod

# ========================
# ✅ Runtime Setup
# ========================
EXPOSE 8001

COPY entrypoint.sh /entrypoint.sh
RUN chown www-data:www-data /entrypoint.sh && chmod +x /entrypoint.sh

USER www-data

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8001"]


