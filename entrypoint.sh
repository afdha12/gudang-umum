#!/bin/sh
set -e  # Fail fast on error

echo "🚀 ENTRYPOINT START"

# Pastikan kita di direktori yang benar
cd /app



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
