#!/bin/bash

echo "🔨 Membangun Laravel image..."
sudo docker compose build

# echo "🎨 Membangun frontend (Tailwind/Vite)..."
# make build-frontend

echo "🚀 Menjalankan Laravel..."
sudo docker compose up -d
