#!/bin/bash
set -e

# Copy .env from example if not present
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate APP_KEY if not set
php artisan key:generate --force 2>/dev/null || true

# Wait for MySQL to be ready
echo "Waiting for MySQL..."
until mysqladmin ping -h"${DB_HOST:-mysql}" -u"${DB_USERNAME:-root}" -p"${DB_PASSWORD:-root}" --silent 2>/dev/null; do
    echo "MySQL not ready yet, retrying in 2s..."
    sleep 2
done
echo "MySQL is ready!"

# Run migrations
php artisan migrate --force

# Start the server
exec php artisan serve --host=0.0.0.0 --port=8000
