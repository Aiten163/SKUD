FROM php:8.3-cli

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    zip unzip libzip-dev libpq-dev libicu-dev \
    iputils-ping \
    && rm -rf /var/lib/apt/lists/*

# Расширения PHP
RUN docker-php-ext-install \
    pdo pdo_mysql pdo_pgsql mbstring exif \
    pcntl bcmath gd zip intl sockets

# Redis
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY ./docker/php-cli/dev/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

ENTRYPOINT ["entrypoint"]
CMD ["php", "artisan", "websocket:listen"]
