# Mengganti file .env dengan pengaturan untuk Docker
echo "Mengatur .env untuk Docker..."
cp .env.docker .env

# Menjalankan Docker Compose dengan port yang disesuaikan
docker-compose down
docker-compose up --build -d

echo "Laravel berjalan di http://localhost:8084"