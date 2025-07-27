FROM php:8.3-cli

# Устанавливаем необходимые пакеты
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    mariadb-client \
    curl \
    netcat-openbsd \
    && docker-php-ext-install pdo_mysql pcntl exif \
    && docker-php-ext-enable exif \
    && apt-get clean

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Создаем пользователя и директорию заранее
RUN groupadd -g 1000 developer && \
    useradd -u 1000 -g 1000 -ms /bin/bash developer && \
    mkdir -p /var/www && \
    chown developer:developer /var/www

# Устанавливаем рабочую директорию
WORKDIR /var/www

# Копируем файлы проекта с правильными правами
COPY --chown=developer:developer ./src /var/www/

# Устанавливаем зависимости
USER developer
RUN composer install --no-interaction --prefer-dist

# Копируем entrypoint
USER root
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
USER developer