FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    mariadb-client \
    curl \
    && docker-php-ext-install pdo_mysql pcntl exif \
    && docker-php-ext-enable exif \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www
