FROM php:8.3-fpm

# Установка всех необходимых зависимостей
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libgd-dev \
    mariadb-client \
    wget \
    unzip \
    curl \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    libavif-bin \
    git \
    && docker-php-ext-configure exif \
    && docker-php-ext-install -j$(nproc) exif pdo_mysql pcntl \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-webp=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
# Установка Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
ARG HOST_UID=1000  # обычно 1000 для первого пользователя Ubuntu
ARG HOST_GID=1000

RUN usermod -u ${HOST_UID} www-data && \
    groupmod -g ${HOST_GID} www-data
# Копирование конфигурации PHP
COPY ./conf.d /usr/local/etc/php/conf.d/

# Установка рабочего каталога
WORKDIR /var/www
