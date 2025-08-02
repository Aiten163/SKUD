FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libmcrypt-dev \
    mariadb-client \
    unzip \
    curl \
    cron \
    acl \
    && docker-php-ext-install pdo_mysql pcntl exif \
    && docker-php-ext-enable exif \
    && rm -rf /var/lib/apt/lists/*

# Создаём группу project и добавляем www-data
RUN groupadd -g 888 skud || true \
    && usermod -aG skud www-data

# Копируем конфиг и entrypoint
COPY dev.ini /usr/local/etc/php/conf.d/dev.ini

WORKDIR /var/www

USER www-data

CMD ["php-fpm"]
