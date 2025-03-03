#!/bin/sh
set -e

echo "Running composer install..."
composer install --prefer-dist --no-interaction --optimize-autoloader

# Cache configuration in production to improve performance.
if [ "$APP_ENV" != "local" ]; then
  echo "Production environment detected. Caching configuration..."
  php artisan config:cache
else
  echo "Local environment detected. Skipping configuration caching."
fi

echo "Running database migrations..."
php artisan migrate --force

echo "Running database migrations..."
php artisan migrate --force

echo "Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan optimize:clear

echo "Generating Swagger documentation..."
php artisan l5-swagger:generate --ansi

echo "Starting the application..."
exec "$@"

