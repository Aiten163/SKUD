#!/bin/bash

echo "👉 Установка зависимостей"
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "✅ Composer install завершён"


echo "🛠️ Artisan команды"
php artisan migrate --force
php artisan db:seed --class=CreateAdminSeeder --force

echo "🚀 Запуск websocket:listen"
exec php artisan websocket:listen
