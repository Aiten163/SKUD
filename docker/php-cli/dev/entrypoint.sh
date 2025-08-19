#!/bin/bash
set -e

    composer install --no-interaction --optimize-autoloader

    # Очистка кэша (пропускаем ошибки)
    php artisan cache:clear || true
    php artisan config:clear || true
    php artisan view:clear || true

    # Миграции и сиды
    php artisan migrate --force --no-interaction
    php artisan db:seed --class=CreateAdminSeeder --force --no-interaction

    # Повторная очистка кэша после миграций
    php artisan cache:clear

exec "$@"