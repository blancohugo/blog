FROM php:8.0-fpm
WORKDIR "/app"

RUN apt-get update \
    && apt-get install -y --no-install-recommends git ssh-client zip unzip libzip-dev \
    && docker-php-ext-install zip \
    && pecl install xdebug-3.0.4 \
    && docker-php-ext-enable xdebug \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

EXPOSE 9000
EXPOSE 9001