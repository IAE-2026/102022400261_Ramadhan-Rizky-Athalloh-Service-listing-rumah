#!/bin/bash
set -e

# Copy .env from example if not present
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate APP_KEY if not set
php artisan key:generate --force 2>/dev/null || true

# Waktu tunggu maksimal 90 detik (45 retries x 2 detik)
echo "Menunggu MySQL siap dan menjalankan migrasi (Maksimal 90 detik)..."
retries=0
while ! php artisan migrate --force; do
    echo "MySQL belum siap atau migrasi gagal, mencoba lagi dalam 2 detik..."
    sleep 2
    retries=$((retries+1))
    if [ "$retries" -ge 45 ]; then
        echo "Waktu tunggu 90 detik habis. Tetap melanjutkan menyalakan server..."
        break
    fi
done
echo "Migrasi database selesai!"

# Menyalakan server agar masuk ke localhost 8000
echo "Menyalakan server di port 8000..."
exec php artisan serve --host=0.0.0.0 --port=8000
