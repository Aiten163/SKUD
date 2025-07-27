#!/bin/bash
set -e

# Ожидание доступности MySQL
timeout=30
while ! nc -z mysql 3306; do
  echo "Waiting for MySQL... (timeout in $timeout seconds)"
  sleep 1
  ((timeout--))
  if [ $timeout -le 0 ]; then
    echo "MySQL connection timed out"
    exit 1
  fi
done

# Выполнение миграций и сидов
echo "Running migrations..."
php artisan migrate --force

echo "Seeding database..."
php artisan db:seed --class=CreateAdminSeeder --force

php artisan websocket:listen

# Основная команда для поддержания работы контейнера
echo "Container is ready"
exec tail -f /dev/null