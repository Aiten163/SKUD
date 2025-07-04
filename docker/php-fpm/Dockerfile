FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libmcrypt-dev \
    mariadb-client \
    unzip \
    curl \
    cron \
    && docker-php-ext-install pdo_mysql pcntl exif \
    && docker-php-ext-enable exif
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
USER www-data
WORKDIR /var/www
