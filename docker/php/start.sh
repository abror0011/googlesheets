#!/bin/bash

# Wait for database to be ready
echo "Waiting for database to be ready..."
while ! php artisan migrate:status > /dev/null 2>&1; do
    echo "Database not ready, waiting..."
    sleep 5
done

# Install dependencies
echo "Installing Composer dependencies..."
composer install --no-interaction

# Generate application key if not exists
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env 2>/dev/null || echo "APP_KEY=" > .env
fi

# Generate application key
echo "Generating application key..."
php artisan key:generate --force

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Create queue tables if they don't exist
echo "Setting up queue tables..."
php artisan queue:table 2>/dev/null || true
php artisan queue:failed-table 2>/dev/null || true
php artisan migrate --force

# Clear caches
echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Create supervisor directories
echo "Setting up supervisor..."
mkdir -p /var/log/supervisor /var/run/supervisor
chown -R www-data:www-data /var/log/supervisor /var/run/supervisor

# Start supervisor in background
echo "Starting supervisor..."
supervisord -c /etc/supervisor/conf.d/supervisord.conf

# Start PHP-FPM
echo "Starting PHP-FPM..."
exec docker-php-entrypoint php-fpm
