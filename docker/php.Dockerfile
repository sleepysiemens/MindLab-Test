FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
        bash \
        git \
        unzip \
        curl \
        libzip-dev \
        zip \
        postgresql-dev \
        autoconf \
        gcc \
        g++ \
        make \
        icu-dev \
        oniguruma-dev \
        libxml2-dev \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo pdo_pgsql zip intl bcmath opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
