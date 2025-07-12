#!/bin/sh

# Salin manifest.json agar Laravel bisa membacanya
if [ -f /app/public/build/.vite/manifest.json ]; then
    echo "Copying Vite manifest.json..."
    cp /app/public/build/.vite/manifest.json /app/public/build/manifest.json
fi

exec "$@"  # Lanjutkan ke CMD utama
