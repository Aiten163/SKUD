FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libmcrypt-dev \
    mariadb-client \
    unzip \
    curl \
    cron \
    && docker-php-ext-install pdo_mysql pcntl exif \
    && docker-php-ext-enable exif

WORKDIR /var/www
