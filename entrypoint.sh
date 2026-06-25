#!/bin/bash
set -e

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Wait for MySQL to be ready
echo "Waiting for MySQL..."
until php artisan db:monitor --max=1 2>/dev/null; do
    sleep 2
done

# Run migrations
php artisan migrate --force

# Start the server
php artisan serve --host=0.0.0.0 --port=8000
