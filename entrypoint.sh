#!/bin/sh
set -e  # Fail fast on error

echo "🚀 ENTRYPOINT START"

# Pastikan kita di direktori yang benar
cd /app

# Salin .env.prod ke .env jika belum ada .env
if [ ! -f ".env" ]; then
  echo "📄 Copying .env.prod to .env"
  cp .env.prod .env
fi

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

# Create storage link
php artisan storage:link || true

# Cache untuk production (aktifkan jika kamu pakai production)
# php artisan config:cache || true

# Test APP_KEY
echo "🧪 Testing APP_KEY..."
php artisan tinker --execute="echo 'APP_KEY: ' . config('app.key');" || echo "Warning: APP_KEY test failed"

echo "✅ Starting Laravel Octane..."
exec "$@"
