#!/bin/sh
set -e

until php -r '
$host = getenv("DB_HOST") ?: "mysql";
$port = getenv("DB_PORT") ?: "3306";
$database = getenv("DB_DATABASE") ?: "laravel_notes";
$username = getenv("DB_USERNAME") ?: "laravel_user";
$password = getenv("DB_PASSWORD") ?: "laravel_pass";

try {
    new PDO("mysql:host={$host};port={$port};dbname={$database}", $username, $password);
    exit(0);
} catch (Throwable $exception) {
    exit(1);
}
'; do
  echo "Waiting for MySQL..."
  sleep 2
done

php artisan migrate --force
php artisan db:seed --force
php artisan serve --host=0.0.0.0 --port=8000

