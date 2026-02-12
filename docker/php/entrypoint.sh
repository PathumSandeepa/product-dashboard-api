#!/bin/bash
set -e

required_vars=("DB_HOST" "DB_PORT" "DB_DATABASE" "DB_USERNAME" "DB_PASSWORD")

echo "Checking required database environment variables..."
for var in "${required_vars[@]}"; do
  if [ -z "$(eval echo "\$${var}")" ]; then
    echo "Error: $var is not set!"
    exit 1
  fi
done

echo "Waiting for database (${DB_HOST}:${DB_PORT})..."

until php -r "
try {
    new PDO(
        'pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD'),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo 'Connected!\n';
} catch (Exception \$e) {
    exit(1);
}
"; do
  echo "Database not ready yet, retrying in 2 seconds..."
  sleep 2
done

echo "Database is ready!"

php artisan config:cache
php artisan route:cache

php artisan migrate --force

exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
