#!/bin/sh
set -e  # Fail fast on error

echo "🚀 ENTRYPOINT START"

# Pastikan kita di direktori yang benar
cd /app

# Cek permissions
echo "📋 Checking permissions..."
ls -la .env .env.prod 2>/dev/null || true

# Backup .env jika bisa, jika tidak langsung copy
if [ -f ".env" ]; then
    echo "📄 Attempting to backup .env"
    cp .env .env.backup 2>/dev/null || echo "⚠️  Cannot backup .env, proceeding without backup"
fi

# Copy .env.prod ke .env
echo "📄 Copying .env.prod to .env"
cat .env.prod > .env 2>/dev/null || {
    echo "❌ Cannot write to .env, checking permissions..."
    ls -la .env .env.prod
    exit 1
}

# Check if artisan exists
if [ ! -f "artisan" ]; then
    echo "❌ Laravel artisan file not found!"
    exit 1
fi

# Clear cache
echo "🧹 Clearing cache..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan config:cache || true

# # Create storage link
# php artisan storage:link || true

# Cache untuk production (aktifkan jika kamu pakai production)
# php artisan config:cache || true

echo "✅ Starting Laravel Octane..."
exec "$@"
