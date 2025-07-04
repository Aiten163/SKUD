FROM php:8.3-cli

# Создаем группу и пользователя
RUN groupadd -g 1000 aiten && \
    useradd -u 1000 -g 1000 -ms /bin/bash aiten

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

# Копируем проект
COPY ./src /var/www/

# ✅ Даем права на storage и bootstrap/cache
RUN mkdir -p storage/logs bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Выполняем composer от root
RUN composer install --no-interaction --prefer-dist

# Переключаемся на пользователя
USER aiten
