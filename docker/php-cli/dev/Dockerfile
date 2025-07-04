FROM php:8.3-cli

# Устанавливаем необходимые пакеты
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    mariadb-client \
    curl \
    && docker-php-ext-install pdo_mysql pcntl exif \
    && docker-php-ext-enable exif \
    && apt-get clean

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Устанавливаем рабочую директорию
WORKDIR /var/www

# Копируем файлы проекта из папки src в контейнер
COPY ./src /var/www/
# Запускаем composer install в правильной директории
RUN composer install --no-interaction --prefer-dist
RUN groupadd -g 1000 developer && \
    useradd -u 1000 -g 1000 -ms /bin/bash developer \

RUN php artisan migrate

USER developer