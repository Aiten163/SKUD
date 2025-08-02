FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    zip \
    && docker-php-ext-install zip pdo pdo_mysql

# Копируем Composer из контейнера Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Копируем исходники проекта
COPY src/ /var/www/

# Копируем entrypoint скрипт
COPY docker/php-cli/dev/entrypoint.sh /entrypoint.sh

WORKDIR /var/www
# Устанавливаем точку входа
ENTRYPOINT ["/entrypoint.sh"]
