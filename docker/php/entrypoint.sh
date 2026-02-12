#!/bin/bash
set -e

echo "Waiting for database..."

until php -r "
try {
    new PDO(
        'pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD')
    );
} catch (Exception \$e) {
    exit(1);
}
"; do
  sleep 2
done

echo "Database is ready!"

# Install composer dependencies if missing
if [ ! -d "vendor" ]; then
  echo "Installing Composer dependencies..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Run migrations automatically (optional but recommended for dev)
php artisan migrate --force

# Start Laravel server
php artisan serve --host=0.0.0.0 --port=8000
