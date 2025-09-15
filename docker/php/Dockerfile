FROM php:8.3-fpm-alpine

# Extensiones 
RUN apk add --no-cache git bash icu-dev oniguruma-dev libzip-dev zlib-dev libpng-dev \
 && docker-php-ext-install -j$(nproc) intl zip pdo_mysql opcache

# Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Config PHP
COPY php.ini /usr/local/etc/php/conf.d/php.ini

# Usuario 
RUN addgroup -g 1000 app && adduser -D -G app -u 1000 app
USER app

WORKDIR /var/www/html